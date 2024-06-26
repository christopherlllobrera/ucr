<?php

namespace App\Filament\Resources\DraftbillResource\RelationManagers;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;

class DraftRelationManager extends RelationManager
{
    protected static string $relationship = 'draft';
    protected static ?string $title = 'Draft Bill Details';
    protected static ?string $heading = 'Draft Bill Details';
    protected static bool $isLazy = false;
    protected static ?string $label = 'Draft Bill';
    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Draft Bill Details')
                    ->schema([
                        TextInput::make('draftbill_number')
                            ->label('Draft Bill No.')
                            ->placeholder('Draft Bill No.')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('draftbill_amount')
                            ->label('Draft Bill Amount')
                            ->placeholder('Draft Bill Amount')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->inputMode('decimal')
                            ->required()
                            ->prefix('₱')
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(2),
                        TextArea::make('draftbill_particular')
                            ->label('Draft Bill Particular')
                            ->columnSpan(3)
                            ->placeholder('Draft Bill Particular')
                            ->reactive()
                            ->required()
                            ->hint(function ($state) {
                                $singleSmsCharactersCount = 255;
                                $charactersCount = strlen($state);
                                $smsCount = 0;
                                if ($charactersCount > 0) {
                                    $smsCount = ceil(strlen($state) / $singleSmsCharactersCount);
                                }
                                $leftCharacters = $singleSmsCharactersCount - ($charactersCount % $singleSmsCharactersCount);
                                return $leftCharacters.' characters';
                            }),
                        FileUpload::make('bill_attachment')
                            ->label('Attachment')
                            ->deletable(true)
                            ->required()
                            ->multiple()
                            ->minFiles(0)
                            ->reorderable()
                            ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            //Storage Setting
                            ->preserveFilenames()
                            ->previewable()
                            ->maxSize(100000) //100MB
                            ->disk('public')
                            ->directory('Draftbill_Attachments')
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                    ])->columnspan(2)->columns(2),
                Section::make('Draft Bill Timeline')
                    ->schema([
                        DatePicker::make('bill_date_created')
                            ->label('Date Created')
                            ->required(),
                        DatePicker::make('bill_date_submitted')
                            ->label('Date Submitted to Client')
                            ->required(),
                        DatePicker::make('bill_date_approved')
                            ->label('Date Approved by Client')
                            ->required(),
                    ])->columnspan(1),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            //->recordTitleAttribute('draftbill_no')
            ->emptyStateHeading('No draft bills')
            ->heading('Draft Bills')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID'),
                Tables\Columns\TextColumn::make('draftbill_number')
                    ->label('Draft Bill No.')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('draftbill_amount')
                    ->label('Draft Bill Amount')
                    ->searchable()
                    ->sortable()
                    ->money('Php'),
                Tables\Columns\TextColumn::make('draftbill_particular')
                    ->label('Draft Bill Particular')
                    ->searchable()
                    ->sortable(),
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
            ->defaultSort('draftbill_number', 'desc')
            ->filters([
            ])
            ->headerActions([
                CreateAction::make('Create Draft Bill')
                    ->label('Create Draft Bill')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Draft Bill Created')
                            ->body('The Draft  Bill has been created successfully')
                            ->iconColor('success')
                            ->duration(5000)
                    )
                    ->successRedirectUrl(route('filament.mli.resources.draftbills.index')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DetachBulkAction::make(),
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);

    }
}
