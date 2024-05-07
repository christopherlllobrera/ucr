<?php

namespace App\Filament\Resources\DraftbillResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DraftRelationManager extends RelationManager
{
    protected static string $relationship = 'draft';
    protected static ?string $title = 'Draft Bill Details';
    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Draft bill Details')
                    ->schema([
                        TextInput::make('draftbill_no')
                            ->label('Draftbill No.')
                            ->unique(ignoreRecord:True)
                            ->placeholder('Draftbill No.')
                            ->columnSpan(1),
                        TextInput::make('draftbill_amount')
                            ->label('Draftbill Amount')
                            ->label('Draftbill Amount')
                            ->inputMode('decimal')
                            ->prefix('₱')
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(2)
                            ->placeholder('Draftbill Amount'),
                        TextInput::make('draftbill_particular')
                            ->label('Draftbill Particular')
                            ->columnSpan(3)
                            ->placeholder('Draftbill Particular'),
                        FileUpload::make('bill_attachment')
                            ->label('Attachment')
                            ->columnSpanFull(),

                    ])->columnspan(2)->columns(2),
                    Section::make('Draft Bill Timeline')
                         ->schema([
                            DatePicker::make('bill_date_created')
                            ->label('Date Created'),
                        DatePicker::make('bill_date_submitted')
                            ->label('Date Submitted'),
                        DatePicker::make('bill_date_approved')
                            ->label('Date Approved'),
                         ])->columnspan(1),
                         ])->columns(3);
           // ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('draftbill_no')
            ->emptyStateHeading('No draft bills yet')
            ->emptyStateDescription('Once you create your first draft bill, it will appear here.')
            ->columns([
                Tables\Columns\TextColumn::make('draftbill_no')
                ->label('Draftbill No.')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('draftbill_amount')
                ->label('Draftbill Amount')
                ->searchable()
                ->sortable()
                ->money('Php'),
                Tables\Columns\TextColumn::make('draftbill_particular'),
                Tables\Columns\TextColumn::make('bill_date_created')
                ->label('Date Created')
                ->searchable()
                ->sortable()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bill_date_submitted')
                ->label('Date Submitted')
                ->searchable()
                ->sortable()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bill_date_approved')
                ->label('Date Approved')
                ->searchable()
                ->sortable()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Draft bill')

                    ->successNotificationTitle('Draft bill created successfully')

                //Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle('Draft bill updated successfully'),
                //Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Draft bill deleted successfully'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}