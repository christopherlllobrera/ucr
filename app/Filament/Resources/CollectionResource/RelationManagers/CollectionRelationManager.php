<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;

class CollectionRelationManager extends RelationManager
{
    protected static bool $isLazy = false;
    protected static string $relationship = 'collection';
    protected static ?string $title = 'Collection Details';

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
                    FileUpload::make('collection_attachment')
                        ->label('Attachments')
                        ->deletable(true)
                        ->multiple()
                        ->minFiles(0)
                        ->reorderable()
                        ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf' ,'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
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
                        ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('tr_posting_date')
                            ->label('TR Posting Date'),
                    ])->columnspan(1),
            ])->columns(3);
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
                    ->label('OR No.')
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
                ->successNotificationTitle('Collection created successfully'),
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
