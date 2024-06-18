<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers\InvoicerelationRelationManager;
use App\Models\accrual;
use App\Models\draftbill;
use App\Models\draftbilldetails;
use App\Models\invoice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

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
                Section::make('Accrual Details')
                    ->schema([
                        Select::make('ucr_ref_id')
                            ->helperText(function ($record) {
                                if ($record) {
                                    // Hide the helper text in edit mode
                                    return null;
                                } else {
                                    // Show the helper text in create mode
                                    return new HtmlString('Select the <strong>UCR Reference ID</strong><br> to auto-fill the fields.');
                                }
                            })
                            ->label('UCR Reference ID')
                            ->placeholder('Select UCR Reference ID')
                            ->disabledOn('edit')
                            ->searchable()
                            ->live()
                            ->preload()
                            ->reactive()
                            ->native(false)
                            ->required()
                            ->relationship('accruals', 'ucr_ref_id')
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
                                    $set('accruals_attachment', $accrual->accruals_attachment);
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
                                    $set('UCR_Park_Doc', null);
                                    $set('draftbill_no', null);
                                    $set('draftbill_amount', null);
                                    $set('draftbill_particular', null);
                                    $set('bill_date_created', null);
                                    $set('bill_date_submitted', null);
                                    $set('bill_date_approved', null);
                                    $set('bill_attachment', null);

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
                                    $set('accruals_attachment', $accrual->accruals_attachment);
                                }
                            }),
                        TextInput::make('client_name')
                            ->label('Client Name')
                            ->maxLength(50)
                            ->reactive()
                            ->readOnly(),
                        TextInput::make('person_in_charge')
                            ->label('Person-in-charge')
                            ->maxLength(32)
                            ->reactive()
                            ->readOnly(),
                        TextInput::make('wbs_no')
                            ->label('WBS No.')
                            ->maxLength(32)
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
                            ->label('UCR Park Doc')
                            ->reactive()
                            ->readOnly(),
                        TextArea::make('particulars')
                            ->label('Particulars')
                            ->reactive()
                            ->columnSpanFull()
                            ->readOnly(),
                        TextInput::make('accrual_amount')
                            ->label('Accrual Amount')
                            ->prefix('₱')
                            ->numeric()
                            ->minValue(1)
                            ->readOnly()
                            ->reactive()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->columnSpanFull()
                            ->inputMode('decimal'),
                        FileUpload::make('accruals_attachment')
                            ->label('Accrual Attachments')
                            ->columnSpanFull()
                            ->multiple()
                            ->minFiles(0)
                            ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            //Storage Setting
                            ->preserveFilenames()
                            ->previewable()
                            ->maxSize(100000) //100MB
                            ->disk('public')
                            ->directory('Accrual_Attachments')
                            ->visibility('public')
                            ->deletable(false)
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->disabled()
                            ->uploadingMessage('Uploading Accrual attachment...'),
                    ])->columnspan(2)->columns(2),
                Section::make('Draft Bill Details')
                    ->schema([
                        Select::make('draftbill_no')
                            ->label('Draft Bill ID.')
                            ->placeholder('Select Draft Bill No.')
                            ->options(fn (Get $get): Collection => draftbill::query()
                                ->where('ucr_ref_id', $get('ucr_ref_id'))->get()
                                ->pluck('id', 'id'))
                                          //$this->record->draftbills->draftbill_no
                            //->relationship('draftbilldetails', 'draftbill_no')
                            ->required()
                            ->validationMessages([
                                'unique' => 'The Draft Bill No. has already been selected.
                            Go to Active Invoice Table to add invoice details',
                            ])
                            ->helperText(function ($record) {
                                if ($record) {
                                    // Hide the helper text in edit mode
                                    return null;
                                } else {
                                    // Show the helper text in create mode
                                    return new HtmlString('Select the <strong>Draft Bill ID</strong> to auto-fill <br>the fields.');
                                }
                            })
                            ->searchable()
                            ->live()
                            ->preload()
                            ->reactive()
                            ->native(false)
                            ->disabledOn('edit')
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
                                    $set('bill_attachment', $draft->bill_attachment);
                                } else {
                                    $set('draftbill_amount', null);
                                    $set('draftbill_particular', null);
                                    $set('bill_date_created', null);
                                    $set('bill_date_submitted', null);
                                    $set('bill_date_approved', null);
                                    $set('ucr_ref_id', null);
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
                                    $set('bill_attachment', null);
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
                                    $set('bill_attachment', $draft->bill_attachment);
                                }
                            }),
                        TextInput::make('draftbill_number')
                            ->label('Draft Bill No.')
                            ->readOnly(),
                        TextInput::make('draftbill_amount')
                            ->label('Draftbill Amount')
                            ->inputMode('decimal')
                            ->prefix('₱')
                            ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->minValue(1)
                            ->readOnly(),
                        DatePicker::make('bill_date_created')
                            ->label('Date Created')
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
                        FileUpload::make('bill_attachment')
                            ->label('Draft Bill Attachment')
                            ->deletable(false)
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
                            ->columnSpanFull()
                            ->disabled(),
                    ])->columnspan(2)->columns(2),

            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Invoice')
            ->paginated([10, 25, 50])
            ->heading('Active Invoice')
            ->striped()
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('accruals.UCR_Park_Doc')
                    ->label('UCR Park Doc')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('accruals.accrual_amount')
                    ->label('Accrual Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('draftbills.draftbill_amount')
                    ->label('Draft Bill Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                BadgeColumn::make('invoicerelation.invoice_posting_amount')
                    ->label('Invoice Posting Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable()
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('draftbills.draftbill_number')
                    ->label('Draft Bill No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                BadgeColumn::make('invoicerelation.accounting_document')
                    ->label('Accounting Doc.')
                    ->searchable()
                    ->sortable()
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('invoicerelation.reversal_doc')
                    ->label('Reversal Doc.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('invoicerelation.billing_statement')
                    ->label('Billing Statement')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            InvoicerelationRelationManager::class,
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
