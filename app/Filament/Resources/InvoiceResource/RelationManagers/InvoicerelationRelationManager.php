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

class InvoicerelationRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicerelation';
    protected static ?string $title = 'Invoice Details';
    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                TextInput::make('reversal_doc')
                    ->label('Reversal Document')
                    ->placeholder('Request ID')
                    ->maxLength(32),
                TextInput::make('gr_amount')
                    ->label('GR Amount')
                    ->prefix('₱')
                    ->numeric()
                    ->inputMode('decimal')
                    ->minValue(1)
                    ->placeholder('GR Amount'),
                TextInput::make('accounting_doc')
                    ->label('Accounting Document')
                    ->maxLength(32)
                    ->placeholder('Accounting Document'),
                TextInput::make('pojo_no')
                    ->label('PO/JO No.')
                    ->placeholder('PO/JO No.'),
                TextInput::make('gr_no_meralco')
                    ->label('GR No.  created by Meralco')
                    ->maxLength(50)
                    ->placeholder('GR NO. created by Meralco'),
                TextInput::make('billing_statement')
                    ->label('Billing Statement No.')
                    ->maxLength(50)
                    ->placeholder('Billing Statement No.'),
                ])->columnspan(2)
                  ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('date_reversal')
                            ->label('Date Reversal'),
                        DatePicker::make('invoice_date_received')
                            ->label('Date Received'),
                        DatePicker::make('invoice_date_approved')
                                ->label('Date Approved (RCA)')
                                ->placeholder('Business Unit'),
                    ])->columnspan(1),
                Section::make('')
                        ->schema([
                            TextInput::make('invoice_posting_amount')
                                ->label('Posted Amount')
                                ->prefix('₱')
                                ->numeric()
                                ->minValue(1)
                                ->placeholder('Posted Amount')
                                ->inputMode('decimal')
                                ->columnSpan(2),
                            DatePicker::make('invoice_posting_date')
                                ->label('Posting Date')
                                ->columnSpan(1),
                                FileUpload::make('invoice_attachment')
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
                                ->directory('Invoice_Attachments')
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->columnSpan(2)
                                ->deletable()
                                // #IMAGE Settings
                                // ->image()
                                // ->imageEditor()
                                // ->imageResizeMode('force')
                                // ->imageCropAspectRatio('8:5')
                                // ->imageResizeTargetWidth('1920')
                                // ->imageResizeTargetHeight('1080')
                                // ->imageEditorViewportWidth('1920')
                                // ->imageEditorViewportHeight('1080'),
                                ,
                            DatePicker::make('invoice_date_forwarded')
                                ->label('Date Forwarded to Client')
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
                Tables\Columns\TextColumn::make('accounting_doc')
                    ->label('Accounting Document')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('billing_statement')
                    ->label('Billing Statement')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('post_amount')
                    ->label('Posted Amount')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                        ->label('Create Invoice')
                        ->successNotificationTitle('Invoice Created successfully')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
