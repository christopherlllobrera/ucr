<?php

namespace App\Filament\Resources;

use DatePeriod;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\accrual;
use App\Models\parkdocument;
use Filament\Forms\Form;
use App\Models\Draftbill;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DraftbillResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DraftbillResource\RelationManagers;
use Faker\Provider\ar_EG\Text;

class DraftbillResource extends Resource
{
    protected static ?string $model = Draftbill::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationLabel = 'Draft Bill';
    protected static ?string $breadcrumb = 'Draft Bill';
    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
        //     Section::make('')
        //     //->description('Please select UCR Reference ID and fill out the UCR Park Doc. below.')
        //     ->schema([
        //         Select::make('ucr_ref_id')
        //         ->relationship('accruals', 'ucr_ref_id')
        //         ->required()
        //         ->searchable()
        //         ->preload()
        //         ->live()
        //         ->reactive()
        //         ->columnSpanFull()
        //         ->unique(ignoreRecord:True)
        //         ->label('UCR Reference ID')
        //         ->afterStateUpdated(function (Get $get, Set $set,){
        //             $accrual = $get('ucr_ref_id');
        //             $accrual = accruals::find($accrual);
        //             $set('client_name', $accrual->client_name);
        //             $set('person_in_charge', $accrual->person_in_charge);
        //             $set('wbs_no', $accrual->wbs_no);
        //             $set('particulars', $accrual->particulars);
        //             $set('period_covered', $accrual->period_covered);
        //             $set('month', $accrual->month);
        //             $set('business_unit', $accrual->business_unit);
        //             $set('contract_type', $accrual->contract_type);
        //             $set('accrual_amount', $accrual->accrual_amount);
        //             //$set('accruals_attachment', $accrual->accruals_attachment);
        //         })
        //         ->AfterStateHydrated(function (Get $get, Set $set,){
        //             if ($get('ucr_ref_id')) {
        //                 $accrual = accruals::find($get('ucr_ref_id'));
        //                 $set('client_name', $accrual->client_name);
        //                 $set('person_in_charge', $accrual->person_in_charge);
        //                 $set('wbs_no', $accrual->wbs_no);
        //                 $set('particulars', $accrual->particulars);
        //                 $set('period_covered', $accrual->period_covered);
        //                 $set('month', $accrual->month);
        //                 $set('business_unit', $accrual->business_unit);
        //                 $set('contract_type', $accrual->contract_type);
        //                 $set('accrual_amount', $accrual->accrual_amount);
        //                 //$set('accruals_attachment', $accrual->accruals_attachment);
        //             }
        //         }),
        //     TextInput::make('person_in_charge')
        //         ->label('Person-in-charge')
        //         ->maxLength(32)
        //         //->placeholder('Person-in-charge')
        //         ->readOnly(),

        //     TextInput::make('wbs_no')
        //         ->label('WBS No.')
        //         ->maxLength(32)
        //         //->placeholder('WBS No.')
        //         ->readOnly(),
        //     TextInput::make('client_name')
        //         ->label('Client Name')
        //         ->maxLength(50)
        //         //->placeholder('Client Name')
        //         ->readOnly(),
        //     TextInput::make('particulars')
        //         ->label('Particulars')
        //         ->maxLength(50)
        //         //->placeholder('Particulars')
        //         //->columnSpanFull()
        //         ->readOnly(),
        //         TextInput::make('accrual_amount')
        //         ->label('Accrual Amount')
        //         ->prefix('₱')
        //         ->numeric()
        //         ->minValue(1)
        //         ->readOnly()
        //         ->columnSpanFull()
        //         //->placeholder('Accrual Amount')
        //         ->inputMode('decimal'),
        //         TextInput::make('UCR_Park_Doc')
        //                 ->label('UCR Park Document No.')
        //                 ->placeholder('UCR Park Document No.')
        //                 ->unique(ignoreRecord:True)
        //                 ->required(),
        //                 //->disabledOn('create'),
        //                 //->hiddenOn('create'),
        //             DatePicker::make('date_accrued')
        //                 ->label('Date Accrued in SAP')
        //                 //->hiddenOn('create'),
        //     ])->columnspan(2)
        //       ->columns(2),
        //     Section::make('')
        //         ->schema([
        //             TextInput::make('business_unit')
        //             ->label('Business Unit')
        //             //->placeholder('Business Unit')
        //             ->readOnly(),
        //             DatePicker::make('period_covered')
        //                 ->label('Period Covered')
        //                 ->readOnly(),
        //             Select::make('month')
        //                 ->label('Month')
        //                 ->options([
        //                     'January' => 'January',
        //                     'February' => 'February',
        //                     'March' => 'March',
        //                     'April' => 'April',
        //                     'May' => 'May',
        //                     'June' => 'June',
        //                     'July' => 'July',
        //                     'August' => 'August',
        //                     'September' => 'September',
        //                     'October' => 'October',
        //                     'November' => 'November',
        //                     'December' => 'December',
        //                 ])
        //                 ->disabled(),

        //             Select::make('contract_type')
        //                     ->label('Contract Type')
        //                     ->options([
        //                         'LCSP' => 'LCSP',
        //                         'OOS' => 'OOS',
        //                     ])
        //                     ->disabled(),
        //         ])->columnspan(1),
        //             Section::make()
        //             ->schema([
        //                 TextInput::make('draftbill_no')
        //                     ->label('Draftbill No.')
        //                     ->placeholder('Draftbill No.'),
        //                 DatePicker::make('bill_date_created')
        //                     ->label('Date Created'),
        //                 TextInput::make('draftbill_amount')
        //                     ->label('Draftbill Amount')
        //                     ->integer()
        //                     ->placeholder('Draftbill Amount'),
        //                 DatePicker::make('bill_date_submitted')
        //                     ->label('Date Submitted'),
        //                 DatePicker::make('bill_date_approved')
        //                     ->label('Date Approved'),
        //                 TextInput::make('draftbill_particular')
        //                     ->label('Draftbill Particular')
        //                     ->placeholder('Draftbill Particular'),
        //                 FileUpload::make('bill_attachment')
        //                     ->label('Draft Bill Attachment')
        //                     ->columnSpanFull(),
        //             ])->columnspan(3)->columns(3),
        //

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
                        TextInput::make('draftbill_no')
                            ->label('Draftbill No.')
                            ->placeholder('Draftbill No.'),
                        DatePicker::make('bill_date_created')
                            ->label('Date Created'),
                        TextInput::make('draftbill_amount')
                            ->label('Draftbill Amount')
                            ->prefix('₱')
                            ->numeric()
                            ->minValue(1)
                            ->inputMode('decimal')
                            ->placeholder('Draftbill Amount'),
                        DatePicker::make('bill_date_submitted')
                            ->label('Date Submitted'),
                        DatePicker::make('bill_date_approved')
                            ->label('Date Approved'),
                        TextInput::make('draftbill_particular')
                            ->label('Draftbill Particular')
                            ->placeholder('Draftbill Particular'),
                        FileUpload::make('bill_attachment')
                            ->label('Draft Bill Attachment')
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
                                ->directory('Accruals_Attachments')
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                            ->columnSpanFull(),
                    ])->columnspan(3)->columns(3),
        ])->columns(3);




}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.date_accrued')
                    ->label('UCR Park Doc')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.period_covered')
                    ->label('Period Covered')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.wbs_no')
                    ->label('WBS No.')
                    ->limit(10)
                    ->toggleable (isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbill_no')
                    ->label('Draftbill No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbill_amount')
                    ->label('Draftbill Amount')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('draftbill_particular')
                    ->label('Draftbill Particular')
                    ->toggleable (isToggledHiddenByDefault: false)
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDraftbills::route('/'),
            'create' => Pages\CreateDraftbill::route('/create'),
            'edit' => Pages\EditDraftbill::route('/{record}/edit'),
        ];
    }
}
