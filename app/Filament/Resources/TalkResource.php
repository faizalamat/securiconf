<?php

namespace App\Filament\Resources;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action){
                return $action->button()->label('Filter');
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(function (Talk $record){
                        return Str::of($record->abstract)->limit(40)  ;
                    }),
                Tables\Columns\ImageColumn::make('speaker.avatar')
                    ->label('Speaker Avatar')
                    ->defaultImageUrl(fn (Talk $talk) => 'https://www.ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($talk->speaker->name))
                    ->circular(),
                Tables\Columns\TextColumn::make('length')
                    ->icon(function ($state){
                        return match($state){
                            TalkLength::NORMAL => 'heroicon-o-megaphone',
                            TalkLength::LIGHTNING => 'heroicon-o-bolt',
                            TalkLength::KEYNOTE => 'heroicon-o-key',
                        };
                        // return 'heroicon-o-bolt';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->color(function ($state){
                        return $state->getColor();
                    }),
                Tables\Columns\ToggleColumn::make('new_talk'),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->numeric()
                    ->sortable(),
                
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('new_talk')
                    ,
                Tables\Filters\SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ,
                Tables\Filters\Filter::make('has_avatar')
                    ->query(function ($query){
                        $query->whereHas('speaker', fn($query) => $query->whereNotNull('avatar'));
                    })
                    // ->options(
                    //     fn (Builder $query) => $query->pluck('name', 'id')
                    // ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->slideOver(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                    ->visible( function ($record){
                        return $record->status === (TalkStatus::SUBMITTED);
                    })
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action( function (Talk $record){
                    $record->approve();
                })->after(function (){
                    Notification::make()->success()->title('This talk was approved')
                    ->duration(1000)
                    ->body('The speaker has been notified and the talk has been added to the conference schedule')
                    ->send();
                }),
                Tables\Actions\Action::make('reject')
                ->visible( function ($record){
                    return $record->status === (TalkStatus::SUBMITTED);
                })
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->requiresConfirmation()
                ->action( function (Talk $record){
                    $record->reject();
                })->after(function (){
                    Notification::make()->danger()->title('This talk was rejected')
                    ->duration(1000)
                    ->body('The speaker has been notified')
                    ->send();
                })
                ]),
                
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                    ->action( function (Collection $records){
                        return $records->each->approve();
                    }),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                ->tooltip('Export all talks')
                ->action(function ($livewire){
                    ray($livewire->getFilteredTableQuery()->count());
                    ray('Exporting...');
                }),
            ]);
            ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            // 'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
