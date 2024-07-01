<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\accrual;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\draftbill;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\DraftbillResource\Pages;
use Filament\Infolists\Components\Section as InfolistSection;
use App\Filament\Resources\DraftbillResource\RelationManagers\DraftRelationManager;

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
                                    $set('date_accrued', null);
                                    $set('UCR_Park_Doc', null);
                                    $set('accruals_attachment', null);
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
                                    $set('accruals_attachment', $accrual->accruals_attachment);
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
                            ->reactive()
                            ->autosize(true)
                            ->rows(2)
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
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->inputMode('decimal')
                            ->minValue(1)
                            ->readOnly()
                            ->reactive()
                            ->columnSpanFull()
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
                        FileUpload::make('accruals_attachment')
                            ->label('Accrual Attachments')
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
                            ->disabled()
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->uploadingMessage('Uploading Accrual attachment...'),
                    ])->columnspan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Draft Bill')
            ->paginated([10, 25, 50])
            ->striped()
            ->heading('Active Draft Bill')
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
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

            InfolistSection::make('Draftbill Details')
                ->icon('heroicon-m-wallet')
                ->schema([
                    TextEntry::make('draft.draftbill_number')
                        ->label('Draft Bill No.')
                        ->weight(FontWeight::Bold)
                        ->icon('heroicon-o-clipboard')
                        ->iconPosition(IconPosition::After)
                        ->copyable()
                        ->copyMessage('Copied!')
                        ->copyMessageDuration(1500),
                    TextEntry::make('draft.draftbill_amount')
                        ->label('Draft Bill Amount')
                        ->money('Php'),
                    TextEntry::make('draft.bill_attachment')
                        ->label('Draft Bill Attachments')
                        ->icon('heroicon-o-attachment'),
                    TextEntry::make('draft.bill_date_created')
                        ->label('Date Created')
                        ->date()
                        ->icon('heroicon-o-calendar-days'),
                    TextEntry::make('draft.bill_date_submitted')
                        ->label('Date Submitted to Client')
                        ->date()
                        ->icon('heroicon-o-calendar-days'),
                    TextEntry::make('draft.bill_date_approved')
                        ->label('Date Approved by Client')
                        ->date()
                        ->icon('heroicon-o-calendar-days'),
                    TextEntry::make('draft.draftbill_particular')
                        ->label('Particular')
                        ->columnSpanFull(),
                ])->columnspan(3)->collapsible()
                    ->columns([
                        'default' => 2,
                        'sm' => 3,
                        'md' => 4,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ]),
                InfolistSection::make('Accruals Details')
                ->icon('heroicon-m-banknotes')
                ->schema([
                    TextEntry::make('accruals.ucr_ref_id')
                        ->label('UCR Reference ID')
                        ->weight(FontWeight::Bold)
                        ->icon('heroicon-o-clipboard')
                        ->iconPosition(IconPosition::After)
                        ->copyable()
                        ->copyMessage('Copied!')
                        ->copyMessageDuration(1500),
                    TextEntry::make('accruals.client_name')
                        ->label('Client'),
                    TextEntry::make('accruals.person_in_charge')
                        ->icon('heroicon-o-user')
                        ->label('Person-in-charge'),
                    TextEntry::make('accruals.wbs_no')
                        ->label('WBS No.'),
                    TextEntry::make('accruals.accrual_amount')
                        ->label('Accrual Amount')
                        ->money('Php'),
                    TextEntry::make('accruals.contract_type')
                        ->label('Contract Type')
                        ->icon('heroicon-o-document-check'),
                    TextEntry::make('accruals.business_unit')
                        ->label('Business Unit')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'Facility Services' => 'gray',
                            'Transport Services' => 'warning',
                            'Warehouse Services' => 'success',
                            'General Services' => 'info',
                            'Cons & Reno Services' => 'primary',
                        }),
                    TextEntry::make('accruals.period_started')
                        ->label('Period started')
                        ->date()
                        ->icon('heroicon-o-calendar-days'),
                    TextEntry::make('accruals.period_ended')
                        ->label('Period ended')
                        ->icon('heroicon-o-calendar-days'),
                    TextEntry::make('accruals.month')
                        ->label('Month')
                        ->icon('heroicon-o-calendar'),
                    TextEntry::make('accruals.UCR_Park_Doc')
                        ->label('UCR Park Doc No.'),
                    TextEntry::make('accruals.date_accrued')
                        ->label('Date Accrued in SAP')
                        ->date()
                        ->icon('heroicon-o-calendar-days'),
                    TextEntry::make('accruals.particulars')
                        ->label('Particulars')
                        ->lineClamp(2)
                        ->columnSpanFull(),
                ])->columnspan(3)->columns([
                        'default' => 2,
                        'sm' => 3,
                        'md' => 4,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ]),
                Fieldset::make('Date created and updated')
                ->schema([
                    TextEntry::make('draft.created_at')
                        ->label('Date Created')
                        ->icon('heroicon-o-calendar-days')
                        ->dateTime(),
                    TextEntry::make('draft.updated_at')
                        ->label('Date Updated')
                        ->icon('heroicon-o-calendar-days')
                        ->dateTime(),
                ])->columnspan(2)->columns([
                    'default' => 1,
                ]),
            ])->columns(3);
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
