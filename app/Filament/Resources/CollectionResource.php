<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers\CollectionRelationManager;
use App\Models\accrual;
use App\Models\collection;
use App\Models\draftbill;
use App\Models\draftbilldetails;
use App\Models\invoice;
use App\Models\invoicedetails;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection as BaseCollection;

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
                    ->schema([
                        Select::make('ucr_ref_id')
                            ->relationship('accruals', 'ucr_ref_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->reactive()
                            ->columnSpan(1)
                            ->placeholder('Select UCR Reference ID')
                            ->label('UCR Reference ID')
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $accrual = $get('ucr_ref_id');
                                if ($accrual) {
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
                                    $set('UCR_Park_Doc', $accrual->UCR_Park_Doc);
                                } else {
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
                                    $set('UCR_Park_Doc', null);
                                }
                            })
                            ->AfterStateHydrated(function (Get $get, Set $set) {
                                if ($get('ucr_ref_id')) {
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
                                    $set('UCR_Park_Doc', $accrual->UCR_Park_Doc);
                                }
                            })
                            ->disabledOn('edit'),
                        TextInput::make('client_name')
                            ->label('Client Name')
                            ->reactive()
                            ->readOnly(),
                        TextInput::make('person_in_charge')
                            ->label('Person-in-charge')
                            ->reactive()
                            ->readOnly(),
                        TextInput::make('wbs_no')
                            ->label('WBS No.')
                            ->reactive()
                            ->readOnly(),
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
                                'LSCP' => 'LSCP',
                                'OOS' => 'OOS',
                            ])
                            ->disabled(),
                        TextInput::make('UCR_Park_Doc')
                            ->label('UCR Park Document')
                            ->reactive()
                            ->readOnly(),
                        TextArea::make('particulars')
                            ->label('Particulars')
                            ->reactive()
                            ->columnspan(2)
                            ->readOnly(),
                        TextInput::make('accrual_amount')
                            ->label('Accrual Amount')
                            ->prefix('₱')
                            ->readOnly()
                            ->reactive()
                            ->columnspan(2),
                    ])->columns(2)->columnspan(2),
                Section::make('Draft Bill Details')
                    ->schema([
                        Select::make('draftbill_no')
                            ->options(fn (Get $get): BaseCollection => draftbill::query()
                                ->where('ucr_ref_id', $get('ucr_ref_id'))->get()
                                ->pluck('id', 'id'))
                            //->relationship('draftbills', 'draftbill_no')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Draftbill No. already exists.',
                            ])
                            ->searchable()
                            ->preload()
                            ->live()
                            ->reactive()
                            ->label('Draftbill ID.')
                            ->placeholder('Select Draftbill No.')
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $draft = $get('draftbill_no');
                                if ($draft) {
                                    $draft = draftbilldetails::find($draft);
                                    $set('draftbill_number', $draft->draftbill_number);
                                    $set('draftbill_amount', $draft->draftbill_amount);
                                    $set('draftbill_particular', $draft->draftbill_particular);
                                    $set('bill_date_created', $draft->bill_date_created);
                                    $set('bill_date_submitted', $draft->bill_date_submitted);
                                    $set('bill_date_approved', $draft->bill_date_approved);
                                } else {
                                    $set('draftbill_amount', null);
                                    $set('draftbill_particular', null);
                                    $set('bill_date_created', null);
                                    $set('bill_date_submitted', null);
                                    $set('bill_date_approved', null);
                                }
                            })
                            ->AfterStateHydrated(function (Get $get, Set $set) {
                                if ($get('draftbill_no')) {
                                    $draft = draftbilldetails::find($get('draftbill_no'));
                                    $set('draftbill_number', $draft->draftbill_number);
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
                        TextInput::make('draftbill_number')
                            ->label('Draftbill No.'),
                        TextInput::make('draftbill_amount')
                            ->label('Draftbill Amount')
                            ->prefix('₱')
                            ->readOnly(),
                        DatePicker::make('bill_date_created')
                            ->label('Draft Bill Date Created')
                            ->readOnly(),
                        DatePicker::make('bill_date_submitted')
                            ->label('Date Submitted to Client')
                            ->readOnly(),
                        DatePicker::make('bill_date_approved')
                            ->label('Date Approved by Client')
                            ->readOnly(),
                        TextArea::make('draftbill_particular')
                            ->label('Draftbill Particular')
                            ->columnSpan(2)
                            ->readOnly(),
                    ])->columns(2)->columnspan(2),
                Section::make('Invoice Details')
                    ->schema([
                        Select::make('accounting_doc')
                            ->options(fn (Get $get): BaseCollection => invoice::query()
                                ->where('ucr_ref_id', $get('ucr_ref_id'))->get()
                                ->pluck('id', 'id'))
                            //->relationship('invoices', 'reversal_doc')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Accounting Document already exists.',
                            ])
                            ->label('Invoice ID')
                            ->placeholder('Select Invoice ID')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $invoice = $get('accounting_doc');
                                if ($invoice) {
                                    $invoice = invoicedetails::find($invoice);
                                    $set('reversal_doc', $invoice->reversal_doc);
                                    $set('gr_amount', $invoice->gr_amount);
                                    $set('date_reversal', $invoice->date_reversal);
                                    $set('accounting_document', $invoice->accounting_document);
                                    $set('invoice_date_received', $invoice->invoice_date_received);
                                    $set('pojo_no', $invoice->pojo_no);
                                    $set('gr_no_meralco', $invoice->gr_no_meralco);
                                    $set('billing_statement', $invoice->billing_statement);
                                    $set('invoice_date_approved', $invoice->invoice_date_approved);
                                    $set('invoice_posting_date', $invoice->invoice_posting_date);
                                    $set('invoice_posting_amount', $invoice->invoice_posting_amount);
                                    $set('invoice_date_forwarded', $invoice->invoice_date_forwarded);
                                } else {
                                    $set('reversal_doc', null);
                                    $set('gr_amount', null);
                                    $set('date_reversal', null);
                                    $set('accounting_document', null);
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
                            ->AfterStateHydrated(function (Get $get, Set $set) {
                                if ($get('accounting_doc')) {
                                    $invoice = invoicedetails::find($get('accounting_doc'));
                                    $set('reversal_doc', $invoice->reversal_doc);
                                    $set('gr_amount', $invoice->gr_amount);
                                    $set('date_reversal', $invoice->date_reversal);
                                    $set('accounting_document', $invoice->accounting_document);
                                    $set('invoice_date_received', $invoice->invoice_date_received);
                                    $set('pojo_no', $invoice->pojo_no);
                                    $set('gr_no_meralco', $invoice->gr_no_meralco);
                                    $set('billing_statement', $invoice->billing_statement);
                                    $set('invoice_date_approved', $invoice->invoice_date_approved);
                                    $set('invoice_posting_date', $invoice->invoice_posting_date);
                                    $set('invoice_posting_amount', $invoice->invoice_posting_amount);
                                    $set('invoice_date_forwarded', $invoice->invoice_date_forwarded);
                                }
                            })
                            ->native(false)
                            ->disabledOn('edit'),
                        TextInput::make('accounting_document')
                            ->label('Accounting Document')
                            ->readOnly(),
                        TextInput::make('reversal_doc')
                            ->label('Reversal Document')
                            ->readOnly(),
                        TextInput::make('gr_amount')
                            ->label('Good Receipt Amount')
                            ->prefix('₱')
                            ->readOnly(),
                        TextInput::make('pojo_no')
                            ->readOnly()
                            ->label('Purchase Order No. / Job Order No.'),
                        TextInput::make('gr_no_meralco')
                            ->label('Good Receipt No. created by Meralco')
                            ->readOnly(),
                        TextInput::make('billing_statement')
                            ->label('Billing Statement No.')
                            ->readOnly(),
                        TextInput::make('invoice_posting_amount')
                            ->label('Posted Amount')
                            ->prefix('₱')
                            ->inputMode('decimal')
                            ->readOnly(),
                        DatePicker::make('date_reversal')
                            ->label('Date Reversal')
                            ->readOnly(),
                        DatePicker::make('invoice_date_received')
                            ->label('Date Received')
                            ->readOnly(),
                        DatePicker::make('invoice_date_approved')
                            ->label('Date Approved (RCA)')
                            ->readOnly(),
                        DatePicker::make('invoice_posting_date')
                            ->label('Posting Date'),
                        DatePicker::make('invoice_date_forwarded')
                            ->label('Date Forwarded to Client')
                            ->readOnly(),
                        DatePicker::make('invoice_posting_date')
                            ->label('Posting Date')
                            ->readOnly(),
                    ])->columnspan(2)
                    ->columns(2),
            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Collection yet')
            ->emptyStateDescription('Once you create your first collection, it will appear here.')
            ->paginated([10, 25, 50])
            ->heading('Active Collection')
            ->columns([
                //accruals
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.ucr_park_doc')
                    ->label('UCR Park Document No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                //invoice
                TextColumn::make('invoices.reversal_doc')
                    ->label('Reversal Document No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoices.accounting_doc')
                    ->label('Accounting Document No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoices.billing_statement')
                    ->label('Billing Statement No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoices.invoice_posting_amount')
                    ->label('Posted Amount')
                    ->searchable()
                    ->sortable(),
                //Collection
                TextColumn::make('collection.amount_collected')
                    ->label('Amount Collected')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('collection.tr_posting_date')
                    ->label('Posting Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('collection.or_number')
                    ->label('OR No.')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('collection.amount_collected', 'desc')
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
