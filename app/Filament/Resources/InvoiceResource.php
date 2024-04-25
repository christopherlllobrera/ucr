<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class InvoiceResource extends Resource
{
    protected static ?string $model = invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Invoice';
    protected static ?int $navigationSort = 4;
    protected static ?string $breadcrumb = 'Invoice';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Select::make('ucr_ref_id')
                    ->relationship('accruals', 'ucr_ref_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->reactive()
                    ->columnSpanFull()
                    ->unique(ignoreRecord:True)
                    ->label('UCR Reference ID'),
                TextInput::make('reversal_doc')
                    ->label('Reversal Document')
                    ->placeholder('Request ID')
                    ->maxLength(32),
                TextInput::make('gr_amount')
                    ->label('GR Amount')
                    ->maxLength(32)
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
                    ->label('Billing Statement No')
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
                        DatePicker::make('invoice_date_forwarded')
                                ->label('Date Forwarded to Client'),

                    ])->columnspan(1),
                Section::make('')
                        ->schema([
                            TextInput::make('invoice_posting_amount')
                                ->label('Posted Amount')
                                ->prefix('₱')
                                ->numeric()
                                ->minValue(1)
                                ->placeholder('Posted Amount')
                                ->inputMode('decimal'),
                            DatePicker::make('invoice_posting_date')
                                ->label('Posting Date'),
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
                        ])->columnspan('full'),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID'),
                TextColumn::make('reversal_doc')
                    ->label('Reversal Doc. No.')
                    ->limit(15),
                TextColumn::make('accounting_doc')
                    ->label('Accounting Doc. No.')
                    ->limit(15),
                TextColumn::make('id')
                    ->label('Invoice No.'),
                TextColumn::make('billing_statement')
                    ->label('Billing Statement No.')
                    ->limit(15),
                TextColumn::make('invoice_posting_amount')
                    ->label('Posted Amount')
                    ->alignCenter(),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
