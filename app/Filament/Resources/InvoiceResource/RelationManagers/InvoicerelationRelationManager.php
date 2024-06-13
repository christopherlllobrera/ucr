<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Pages\Actions\Action;

class InvoicerelationRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicerelation';
    protected static ?string $title = 'Invoice Details';
    protected static bool $isLazy = false;
    protected static ?string $label = 'Invoice';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                TextInput::make('reversal_doc')
                    ->label('Reversal Document')
                    ->placeholder('Reversal Document')
                    ->required()
                    ->maxLength(32),
                TextInput::make('gr_amount')
                    ->label('Good Receipt Amount')
                    ->prefix('₱')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->minValue(1)
                    ->placeholder('GR Amount'),
                TextInput::make('accounting_document')
                    ->label('Accounting Document')
                    ->placeholder('Accounting Document')
                    ->required()
                    ->maxLength(32)
                    ->unique(ignoreRecord:true),
                TextInput::make('pojo_no')
                    ->label('PO/JO No.')
                    ->placeholder('PO/JO No.')
                    ->required(),
                TextInput::make('gr_no_meralco')
                    ->label('GR No. created by Meralco')
                    ->placeholder('Good Receipt NO. created by Meralco')
                    ->required()
                    ->maxLength(50),
                TextInput::make('billing_statement')
                    ->label('Billing Statement No.')
                    ->placeholder('Billing Statement No.')
                    ->maxLength(50)
                    ->required(),
                ])->columnspan(2)
                  ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('date_reversal')
                            ->label('Date Reversal')
                            ->required(),
                        DatePicker::make('invoice_date_received')
                            ->label('Date Received')
                            ->required(),
                        DatePicker::make('invoice_date_approved')
                            ->label('Date Approved (RCA)')
                            ->placeholder('Business Unit')
                            ->required(),
                    ])->columnspan(1),
                Section::make('')
                        ->schema([
                            TextInput::make('invoice_posting_amount')
                                ->label('Posted Amount')
                                ->placeholder('Posted Amount')
                                ->required()
                                ->prefix('₱')
                                ->numeric()
                                ->minValue(1)
                                ->inputMode('decimal')
                                ->columnSpan(2),
                            DatePicker::make('invoice_posting_date')
                                ->label('Posting Date')
                                ->required()
                                ->columnSpan(1),
                            FileUpload::make('invoice_attachment')
                                ->label('Attachments')
                                ->required()
                                ->deletable(true)
                                ->multiple()
                                ->minFiles(0)
                                ->reorderable()
                                ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf' ,'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                                //Storage Setting
                                ->preserveFilenames()
                                ->previewable()
                                ->maxSize(100000) //100MB
                                ->disk('public')
                                ->directory('Invoice_Attachments')
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->columnSpan(2)
                                ->deletable(),
                                // #IMAGE Settings
                                // ->image()
                                // ->imageEditor()
                                // ->imageResizeMode('force')
                                // ->imageCropAspectRatio('8:5')
                                // ->imageResizeTargetWidth('1920')
                                // ->imageResizeTargetHeight('1080')
                                // ->imageEditorViewportWidth('1920')
                                // ->imageEditorViewportHeight('1080'),
                            DatePicker::make('invoice_date_forwarded')
                                ->label('Date Forwarded to Client')
                                ->required()
                                ->columnSpan(1),
                        ])->columns(3),
                    // Section::make()
                    //     ->schema([
                    //     TextInput::make('UCR_Park_Doc')
                    //         ->label('UCR Park Document No.')
                    //         ->placeholder('UCR Park Document No.'),
                    //         //->disabledOn('create'),
                    //         //->hiddenOn('create'),
                    //     DatePicker::make('Date_Accrued')
                    //         ->label('Date Accrued in SAP')
                    //         //->hiddenOn('create'),
                    //     ])->columnspan(1)->columns(1)//->hiddenOn('create'),
            ])->columns(3);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reversal_doc')
            ->emptyStateHeading('No invoice yet')
            ->emptyStateDescription('Once you create your first invoice, it will appear here.')
            ->columns([
                Tables\Columns\TextColumn::make('reversal_doc')
                    ->label('Reversal Document')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('accounting_document')
                    ->label('Accounting Document')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gr_amount')
                    ->label('GR Amount')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('gr_no_meralco')
                    ->label('GR No. Created by Meralco')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('pojo_no')
                    ->label('PO/JO No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('billing_statement')
                    ->label('Billing Statement')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_posting_amount')
                    ->label('Posted Amount')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('invoice_posting_date')
                    ->label('Posting Date')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                        ->label('Create Invoice')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Invoice Created')
                                ->body('The Invoice has been created successfully')
                                ->iconColor('success')
                                ->duration(5000),
                        )
                        //->keyBindings(['command+s', 'ctrl+s']),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
        ]);
    }

}
