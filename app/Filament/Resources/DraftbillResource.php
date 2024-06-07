<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DraftbillResource\Pages;
use App\Filament\Resources\DraftbillResource\RelationManagers\DraftRelationManager;
use App\Models\accrual;
use App\Models\draftbill;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                Section::make('Accruals Details')
                    ->schema([
                        Select::make('ucr_ref_id')
                            ->helperText(new HtmlString('Select the <strong>UCR Reference ID</strong> to auto-fill the fields.'))
                            ->relationship('accruals', 'ucr_ref_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->reactive()
                            ->label('UCR Reference ID')
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'The UCR Reference ID has already been selected.
                        Go to Active Draft Bill Table to add Draft Bill details',
                            ])
                            // ->icon('heroicon-o-clipboard')
                            // ->iconPosition(IconPosition::After)
                            ->placeholder('Select UCR Reference ID')
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
                                    $set('date_accrued', $accrual->date_accrued);
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
                                    $set('date_accrued', null);
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
                                    $set('date_accrued', $accrual->date_accrued);
                                    $set('UCR_Park_Doc', $accrual->UCR_Park_Doc);
                                }
                            })
                            ->disabledOn('edit'),
                        TextInput::make('client_name')
                            ->label('Client Name')
                            ->maxLength(50)
                            ->reactive()
                            //->placeholder('Client Name')
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
                        TextArea::make('particulars')
                            ->label('Particulars')
                            ->maxLength(255)
                            ->reactive()
                            ->autosize(true)
                            ->rows(2)
                            //->placeholder('Particulars')
                            ->columnSpanFull()
                            ->hint(function ($state) {
                                $singleSmsCharactersCount = 255;
                                $charactersCount = strlen($state);
                                $smsCount = 0;
                                if ($charactersCount > 0) {
                                    $smsCount = ceil(strlen($state) / $singleSmsCharactersCount);
                                }
                                $leftCharacters = $singleSmsCharactersCount - ($charactersCount % $singleSmsCharactersCount);

                                return $leftCharacters.' characters';
                            })
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
                        DatePicker::make('date_accrued')
                            ->label('Date Accrued in SAP')
                            ->readOnly(),
                        TextInput::make('UCR_Park_Doc')
                            ->label('UCR Park Document No.')
                            ->reactive()
                            ->readOnly(),
                    ])->columnspan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Draft Bill yet')
            ->emptyStateDescription('Once you create your first draft bill it will appear here.')
            ->paginated([10, 25, 50])
            ->striped()
            ->heading('Active Draft Bills')
            ->columns([
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('accruals.UCR_Park_Doc')
                    ->label('UCR Park Doc.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                BadgeColumn::make('draft.draftbill_number')
                    ->color('info')
                    ->label('Draft Bill No.')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('accruals.accrual_amount')
                    ->label('Accrual Amount')
                    ->searchable()
                    ->sortable()
                    ->money('Php'),
                BadgeColumn::make('draft.draftbill_amount')
                    ->label('Draft Bill Amount')
                    ->sortable()
                    ->color('primary')
                    ->money('Php')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('accruals.period_started')
                    ->label('Period Started')
                    ->searchable()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('accruals.period_ended')
                    ->label('Period Ended')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('accruals.wbs_no')
                    ->label('WBS No.')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('draft.draftbill_particular')
                    ->label('Particular')
                    ->searchable()
                    ->sortable()
                    ->wrap(3)
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')

            ->filters([
                //
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
            DraftRelationManager::class,
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
