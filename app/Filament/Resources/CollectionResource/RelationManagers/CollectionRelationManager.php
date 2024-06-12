<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CollectionRelationManager extends RelationManager
{
    protected static bool $isLazy = false;
    protected static string $relationship = 'collection';
    protected static ?string $title = 'Collection Details';
    protected static ?string $label = 'Collection';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([

                        TextInput::make('amount_collected')
                            ->label('Amount Collected')
                            ->maxLength(32)
                            ->prefix('₱')
                            ->numeric()
                            ->minValue(1)
                            ->inputMode('decimal')
                            ->placeholder('Amount Collected'),
                        TextInput::make('or_number')
                            ->label('OR No.')
                            ->maxLength(32)
                            ->placeholder('OR No.'),
                        DatePicker::make('tr_posting_date')
                            ->label('TR Posting Date'),
                        FileUpload::make('collection_attachment')
                            ->label('Attachments')
                            ->deletable(true)
                            ->multiple()
                            ->minFiles(0)
                            ->reorderable()
                            ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            //Storage Setting
                            ->preserveFilenames()
                            ->previewable()
                            ->maxSize(100000) //100MB
                            ->disk('local')
                            ->directory('Collection_Attachments')
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            // #IMAGE Settings
                            // ->image()
                            // ->imageEditor()
                            // ->imageResizeMode('force')
                            // ->imageCropAspectRatio('8:5')
                            // ->imageResizeTargetWidth('1920')
                            // ->imageResizeTargetHeight('1080')
                            // ->imageEditorViewportWidth('1920')
                            // ->imageEditorViewportHeight('1080'),
                            ->columnSpanFull(),
                    ])->columnspan(2)
                    ->columns(3),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount_collected')
            ->emptyStateHeading('No Collection yet')
            ->emptyStateDescription('Once you create your first collection, it will appear here.')
            ->columns([
                TextColumn::make('amount_collected')
                    ->label('Amount Collected')
                    ->searchable()
                    ->sortable()
                    ->money('Php'),
                TextColumn::make('or_number')
                    ->label('Official Receipt No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tr_posting_date')
                    ->label('TR Posting Date')
                    ->searchable()
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Collection')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Collection Created')
                            ->body('The Collection has been created successfully')
                            ->iconColor('success')
                            ->duration(5000)
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Collection deleted successfully'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
