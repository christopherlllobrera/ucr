<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\accrual;
use App\Models\invoice;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\draftbill;
use App\Models\collection;
use Filament\Tables\Table;
use App\Models\invoicedetails;
use App\Models\draftbilldetails;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CollectionResource\Pages;
use Illuminate\Support\Collection as BaseCollection;
use App\Filament\Resources\CollectionResource\RelationManagers;
use App\Filament\Resources\CollectionResource\Widgets\CollectionStats;
use App\Filament\Resources\CollectionResource\RelationManagers\CollectionRelationManager;

class CollectionResource extends Resource
{
    protected static ?string $model = collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Collection';
    protected static ?int $navigationSort = 5;
    protected static ?string $breadcrumb = 'Collection';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Accruals Details')
            ->description('Please select UCR Reference ID to auto-fill the fields.')
            ->schema([
                Select::make('ucr_ref_id')
                ->relationship('accruals', 'ucr_ref_id')
                ->required()
                ->searchable()
                ->preload()
                ->live()
                ->reactive()
                ->columnSpan(1)
                ->label('UCR Reference ID')
                ->afterStateUpdated(function (Get $get, Set $set,){
                    $accrual = $get('ucr_ref_id');
                    if ($accrual){
                        $accrual = accrual::find($accrual);
                        $set('client_name', $accrual->client_name);
                        $set('person_in_charge', $accrual->person_in_charge);
                        $set('wbs_no', $accrual->wbs_no);
                        $set('particulars', $accrual->particulars);
                        $set('period_started', $accrual->period_started);
                        $set('period_ended', $accrual->period_ended);
                        $set('month', $accrual->month);
                        $set('business_unit', $accrual->business_unit);
                        $set('contract_type', $accrual->contract_type);
                        $set('accrual_amount', $accrual->accrual_amount);
                    }
                    else {
                        $set('client_name', null);
                        $set('person_in_charge', null);
                        $set('wbs_no', null);
                        $set('particulars', null);
                        $set('period_started', null);
                        $set('period_ended', null);
                        $set('month', null);
                        $set('business_unit', null);
                        $set('contract_type', null);
                        $set('accrual_amount', null);
                        $set('draftbill_no', null);
                        $set('draftbill_amount', null);
                        $set('draftbill_particular', null);
                        $set('bill_date_created', null);
                        $set('bill_date_submitted', null);
                        $set('bill_date_approved', null);
                    }

                })
                ->AfterStateHydrated(function (Get $get, Set $set,){
                    if ($get('ucr_ref_id' )) {
                        $accrual = accrual::find($get('ucr_ref_id'));
                        $set('client_name', $accrual->client_name);
                        $set('person_in_charge', $accrual->person_in_charge);
                        $set('wbs_no', $accrual->wbs_no);
                        $set('particulars', $accrual->particulars);
                        $set('period_started', $accrual->period_started);
                        $set('period_ended', $accrual->period_ended);
                        $set('month', $accrual->month);
                        $set('business_unit', $accrual->business_unit);
                        $set('contract_type', $accrual->contract_type);
                        $set('accrual_amount', $accrual->accrual_amount);
                    }
                })
                ->disabledOn('edit'),
                TextInput::make('client_name')
                    ->label('Client Name')
                    ->maxLength(50)
                    ->reactive()
                    //->placeholder('Client Name')
                    ->columnSpan(2)
                    ->readOnly(),
                TextInput::make('person_in_charge')
                    ->label('Person-in-charge')
                    ->maxLength(32)
                    ->reactive()
                    //->placeholder('Person-in-charge')
                    ->readOnly(),
                TextInput::make('wbs_no')
                    ->label('WBS No.')
                    ->maxLength(32)
                    ->reactive()
                    //->placeholder('WBS No.')
                    ->readOnly(),

                TextInput::make('particulars')
                    ->label('Particulars')
                    ->maxLength(50)
                    ->reactive()
                    //->placeholder('Particulars')
                    ->columnSpanFull()
                    ->readOnly(),
                TextInput::make('accrual_amount')
                    ->label('Accrual Amount')
                    ->prefix('₱')
                    ->numeric()
                    ->minValue(1)
                    ->readOnly()
                    ->reactive()
                    ->columnSpanFull()
                    //->placeholder('Accrual Amount')
                    ->inputMode('decimal'),
                ])->columnspan(2)
                ->columns(2),
            Section::make('')
                ->schema([
                    DatePicker::make('period_started')
                        ->label('Period Started')
                        ->reactive()
                        ->readOnly(),
                    DatePicker::make('period_ended')
                        ->label('Period Ended')
                        ->reactive()
                        ->readOnly(),
                    Select::make('month')
                        ->label('Month')
                        ->reactive()
                        ->options([
                            'January' => 'January',
                            'February' => 'February',
                            'March' => 'March',
                            'April' => 'April',
                            'May' => 'May',
                            'June' => 'June',
                            'July' => 'July',
                            'August' => 'August',
                            'September' => 'September',
                            'October' => 'October',
                            'November' => 'November',
                            'December' => 'December',
                        ])
                        ->disabled(),
                    Select::make('business_unit')
                        ->label('Business Unit')
                        ->disabled()
                        ->reactive()
                        ->options([
                            'Facility Services' => 'Facility Services',
                            'Transport Services' => 'Transport Services',
                            'Warehouse Services' => 'Warehouse Services',
                            'General Services' => 'General Services',
                            'Cons & Reno Services' => 'Cons & Reno Services',
                        ]),
                    Select::make('contract_type')
                        ->label('Contract Type')
                        ->reactive()
                        ->options([
                            'LCSP' => 'LCSP',
                            'OOS' => 'OOS',
                        ])
                        ->disabled(),
                ])->columnspan(1),
                    Section::make('Draft bill Details')
                        ->description('Please select draftbill no. to auto-fill the fields.')
                    ->schema([
                        Select::make('draftbill_no')
                            ->options(fn(Get $get): BaseCollection => draftbill::query()
                                ->where('ucr_ref_id', $get('ucr_ref_id'))->get()
                                ->pluck('ucr_ref_id', 'id'))
                            ->required()
                            ->unique(ignoreRecord:true)
                            ->validationMessages([
                                'unique' => 'Draftbill No. already exists.',
                            ])
                            ->searchable()
                            ->preload()
                            ->live()
                            ->reactive()
                            ->columnSpan(1)
                            ->label('Draftbill No.')
                            ->afterStateUpdated(function (Get $get, Set $set,){
                                $draft = $get('draftbill_no');
                                if ($draft) {
                                    $draft = draftbilldetails::find($draft);
                                    $set('draftbill_amount', $draft->draftbill_amount);
                                    $set('draftbill_particular', $draft->draftbill_particular);
                                    $set('bill_date_created', $draft->bill_date_created);
                                    $set('bill_date_submitted', $draft->bill_date_submitted);
                                    $set('bill_date_approved', $draft->bill_date_approved);
                                }
                                else {
                                    $set('draftbill_amount', null);
                                    $set('draftbill_particular', null);
                                    $set('bill_date_created', null);
                                    $set('bill_date_submitted', null);
                                    $set('bill_date_approved', null);
                                }
                            })
                            ->AfterStateHydrated(function (Get $get, Set $set,){
                                if ($get('draftbill_no')) {
                                    $draft = draftbilldetails::find($get('draftbill_no'));
                                    $set('draftbill_amount', $draft->draftbill_amount);
                                    $set('draftbill_particular', $draft->draftbill_particular);
                                    $set('bill_date_created', $draft->bill_date_created);
                                    $set('bill_date_submitted', $draft->bill_date_submitted);
                                    $set('bill_date_approved', $draft->bill_date_approved);
                                }
                            })
                            ->disabled('urc_ref_id' === null)
                            ->native(false)
                            ->disabledOn('edit'),
                        TextInput::make('draftbill_amount')
                            ->label('Draftbill Amount')
                            ->label('Draftbill Amount')
                            ->inputMode('decimal')
                            ->prefix('₱')
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(2)
                            ->readOnly(),
                        TextInput::make('draftbill_particular')
                            ->label('Draftbill Particular')
                            ->columnSpan(3)
                            ->readOnly(),
                    ])->columnspan(2)->columns(2),
                    Section::make()
                         ->schema([
                            DatePicker::make('bill_date_created')
                            ->label('Date Created')
                            ->readOnly(),
                        DatePicker::make('bill_date_submitted')
                            ->label('Date Submitted')
                            ->readOnly(),
                        DatePicker::make('bill_date_approved')
                            ->label('Date Approved')
                            ->readOnly(),
                         ])->columnspan(1),

                Section::make()
                ->schema([
                Select::make('reversal_doc')
                    ->options(fn(Get $get): BaseCollection => invoice::query()
                    ->where('ucr_ref_id', $get('ucr_ref_id'))->get()
                    ->pluck('draftbill_no', 'id'))
                    ->required()
                    ->unique(ignoreRecord:true)
                    ->validationMessages([
                        'unique' => 'Reversal Document already exists.',
                    ])
                    ->label('Reversal Document')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->reactive()
                    ->columnSpan(1)
                    ->afterStateUpdated(function (Get $get, Set $set,){
                        $invoice = $get('reversal_doc');
                        if ($invoice) {
                            $invoice = invoicedetails::find($invoice);
                            $set('gr_amount', $invoice->gr_amount);
                            $set('date_reversal', $invoice->date_reversal);
                            $set('accounting_doc', $invoice->accounting_doc);
                            $set('invoice_date_received', $invoice->invoice_date_received);
                            $set('pojo_no', $invoice->pojo_no);
                            $set('gr_no_meralco', $invoice->gr_no_meralco);
                            $set('billing_statement', $invoice->billing_statement);
                            $set('invoice_date_approved', $invoice->invoice_date_approved);
                            $set('invoice_posting_date', $invoice->invoice_posting_date);
                            $set('invoice_posting_amount', $invoice->invoice_posting_amount);
                            $set('invoice_date_forwarded', $invoice->invoice_date_forwarded);
                        }
                        else {
                            $set('reversal_dic', null);
                            $set('gr_amount', null);
                            $set('date_reversal', null);
                            $set('accounting_doc', null);
                            $set('invoice_date_received', null);
                            $set('pojo_no', null);
                            $set('gr_no_meralco', null);
                            $set('billing_statement', null);
                            $set('invoice_date_approved', null);
                            $set('invoice_posting_date', null);
                            $set('invoice_posting_amount', null);
                            $set('invoice_date_forwarded', null);
                        }
                    })
                    // ->AfterStateHydrated(function (Get $get, Set $set,){
                    //     if ($get('reversal_doc')) {
                    //         $invoice = invoicedetails::find($get('reverse_doc'));
                    //         $set('gr_amount', $invoice->gr_amount);
                    //         $set('date_reversal', $invoice->date_reversal);
                    //         $set('accounting_doc', $invoice->accounting_doc);
                    //         $set('invoice_date_received', $invoice->invoice_date_received);
                    //         $set('pojo_no', $invoice->pojo_no);
                    //         $set('gr_no_meralco', $invoice->gr_no_meralco);
                    //         $set('billing_statement', $invoice->billing_statement);
                    //         $set('invoice_date_approved', $invoice->invoice_date_approved);
                    //         $set('invoice_posting_date', $invoice->invoice_posting_date);
                    //         $set('invoice_posting_amount', $invoice->invoice_posting_amount);
                    //         $set('invoice_date_forwarded', $invoice->invoice_date_forwarded);
                    //     }
                    // })
                    ->native(false)
                    ->disabledOn('edit'),
                TextInput::make('gr_amount')
                    ->label('GR Amount')
                    ->prefix('₱')
                    ->numeric()
                    ->inputMode('decimal')
                    ->minValue(1),
                TextInput::make('accounting_doc')
                    ->label('Accounting Document')
                    ->maxLength(32),
                TextInput::make('pojo_no')
                    ->label('PO/JO No.'),
                TextInput::make('gr_no_meralco')
                    ->label('GR No.  created by Meralco')
                    ->maxLength(50),
                TextInput::make('billing_statement')
                    ->label('Billing Statement No.')
                    ->maxLength(50),
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
                Section::make('Invoice Details')
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
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Collection yet')
            ->emptyStateDescription('Once you create your first collection, it will appear here.')
            ->paginated([10, 25, 50, 100,])
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('accruals.ucr_park_doc')
                //     ->label('UCR Park Document No.')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('invoicedetails.reversal_doc')
                //     ->label('Reversal Document No.')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('invoicedetails.accounting_doc')
                //     ->label('Accounting Document No.')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('invoicedetails.billing_statement')
                //     ->label('Billing Statement No.')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('invoicedetails.invoice_amount')
                //     ->label('Invoice Amount')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('invoicedetails.invoice_posting_date')
                //     ->label('Posting Date')
                //     ->searchable()
                //     ->sortable(),
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
            CollectionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }


}
