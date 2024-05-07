<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\accrual;
use App\Models\invoice;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Filament\Resources\InvoiceResource\RelationManagers\InvoicerelationRelationManager;
use App\Models\draftbilldetails;
use App\Models\draftbill;
use Illuminate\Support\Collection;


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
                            ->options(fn(Get $get): Collection => draftbill::query()
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
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Invoice yet')
            ->emptyStateDescription('Once you create your first invoice, it will appear here.')
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID'),
                TextColumn::make('accruals.UCR_Park_Doc')
                    ->label('UCR Park Doc')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.accrual_amount')
                    ->label('Accrual Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbills.draftbill_no')
                    ->label('Draftbill No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbills.draftbill_amount')
                    ->label('Draftbill Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable(),
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
