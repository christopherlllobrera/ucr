<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccrualsResource\Pages;
use App\Filament\Resources\AccrualsResource\Widgets\AccrualStats;
use App\Models\accrual;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class AccrualsResource extends Resource
{
    protected static ?string $model = accrual::class;

    protected static ?string $navigationLabel = 'Accrual';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 1;

    protected static bool $softDelete = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('ucr_ref_id')
                            ->default(function () {
                                $lastUcrRefId = accrual::orderBy('ucr_ref_id', 'desc')->first()?->ucr_ref_id;
                                if ($lastUcrRefId) {
                                    // Extract the numeric part (assuming format "UCR-CE-xxxxxx")
                                    $lastNumber = (int) Str::afterLast($lastUcrRefId, '-');

                                    return 'UCR-CE-'.str_pad(++$lastNumber, 6, '0', STR_PAD_LEFT);
                                } else {
                                    // Handle the case where no UCR reference IDs exist yet
                                    // (Consider a default starting point or user input)
                                    return 'UCR-CE-000999'; // Example default (adjust as needed)
                                }
                            })
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(32)
                            ->unique(accrual::class, 'ucr_ref_id', ignoreRecord: true)
                            ->label('UCR Reference ID')
                            ->placeholder('UCR Reference ID'),
                        TextInput::make('client_name')
                            ->label('Client')
                            //->autocomplete()
                            ->hint(function ($record) {
                                if ($record) {
                                    return null;
                                } else {
                                    return new HtmlString('Use <strong>Uppercase</strong>');
                                }
                            })
                            ->extraInputAttributes(['onChange' => 'this.value = this.value.toUpperCase()'])
                            ->datalist([
                                'ATIMONAN ONE ENERGY  INC.',
                                'MERALCO',
                                'MIDC',
                                'MIESCOR',
                                'MSERV',
                                'MSERV (MERALCO ENERGY)',
                                'MSPECTRUM',
                                'MVP',
                                'MWCI (MANILA WATER COMPANY INC.)',
                                'MOVEM',
                                'NLEX CORPORATION',
                                'OCEANA',
                                'PDRF',
                                'RADIUS TELECOM INC.',
                                'ROBINSONS GROUP',
                                'SHIN CLARK POWER CORPORATION',
                                'SM RETAIL  INC.',
                                'TAKASAGO INTERNATIONAL (PHILIPPINES) INC.',
                                'TEH HSIN ENTERPRISE PHIL. CORPORATION',
                            ])
                            ->placeholder('Client')
                            ->maxLength(50)
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class),
                        TextInput::make('person_in_charge')
                            ->label('Person-in-charge')
                            ->maxLength(32)
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->placeholder('Person-in-charge'),
                        TextInput::make('wbs_no')
                            ->label('WBS No.')
                            ->maxLength(25)
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->placeholder('WBS No.'),
                        TextArea::make('particulars')
                            ->label('Particulars')
                            ->maxLength(255)
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->placeholder('Particulars')
                            ->columnSpan('full')
                            ->reactive()
                            ->autosize(true)
                            ->rows(5)
                            ->hint(function ($state) {
                                $singleSmsCharactersCount = 255;
                                $charactersCount = strlen($state);
                                $smsCount = 0;
                                if ($charactersCount > 0) {
                                    $smsCount = ceil(strlen($state) / $singleSmsCharactersCount);
                                }
                                $leftCharacters = $singleSmsCharactersCount - ($charactersCount % $singleSmsCharactersCount);

                                return $leftCharacters.' characters';
                            }),
                        TextInput::make('accrual_amount')
                            ->label('Accrual Amount')
                            ->prefix('₱')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->inputMode('decimal')
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->placeholder('Accrual Amount')
                            ->columnSpanFull(),
                        FileUpload::make('accruals_attachment')
                            ->label('Attachments')
                            ->multiple()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minFiles(0)
                            ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                                //Storage Setting
                            ->preserveFilenames()
                            ->previewable()
                            ->maxSize(100000) //100MB
                            ->disk('public')
                            ->directory('Accrual_Attachments')
                            ->visibility('public')
                            ->deletable(true)
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->uploadingMessage('Uploading Accrual attachment...')
                            ->columnSpanFull(),
                    ])->columnspan(2)
                    ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('period_started')
                            ->label('Period started')
                            ->required()
                            //->visibleOn(auth()->user()->hasPermissionTo('edit-park-doc')) // still not working
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->minDate(now()->subYears(3)),
                        DatePicker::make('period_ended')
                            ->label('Period ended')
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->minDate(now()->subYears(3))
                            ->afterOrEqual('period_started'),
                        Select::make('month')
                            ->label('Month')
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
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
                            ]),

                        Select::make('contract_type')
                            ->label('Contract Type')
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->options([
                                'LSCP' => 'LCSP',
                                'OOS' => 'OOS',
                            ]),
                        Select::make('business_unit')
                            ->label('Business Unit')
                            ->required()
                            ->disabledOn(Pages\EditAccrualsParkDoc::class)
                            ->options([
                                'Facility Services' => 'Facility Services',
                                'Transport Services' => 'Transport Services',
                                'Warehouse Services' => 'Warehouse Services',
                                'General Services' => 'General Services',
                                'Cons & Reno Services' => 'Cons & Reno Services',
                            ]),
                        TextInput::make('UCR_Park_Doc')
                            ->label('UCR Park Document No.')
                            ->placeholder('UCR Park Document No.')
                            ->required()
                            ->numeric()
                            ->required(fn (string $operation): bool => $operation === 'edit')
                            ->hiddenOn([Pages\EditAccruals::class, Pages\CreateAccruals::class]),
                        DatePicker::make('date_accrued')
                            ->label('Date Accrued in SAP')
                            ->required(fn (string $operation): bool => $operation === 'edit')
                            ->hiddenOn([Pages\EditAccruals::class, Pages\CreateAccruals::class]),
                    ])->columnspan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('No Accruals')
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->weight(FontWeight::Bold)
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('client_name')
                    ->label('Client')
                    ->searchable()
                    ->wrap(2)
                    ->sortable(),
                TextColumn::make('accrual_amount')
                    ->label('Accrual Amount')
                    ->money('Php')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_accrued')
                    ->label('Date Accrued in SAP')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('UCR_Park_Doc')
                    ->label('UCR Park Doc No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('business_unit')
                    ->label('Business Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period_started')
                    ->label('Period Started')
                    ->searchable()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('period_ended')
                    ->label('Period Ended')
                    ->searchable()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('month')
                    ->label('Month')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('wbs_no')
                    ->label('WBS No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contract_type')
                    ->label('Contract Type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('ucr_ref_id', 'desc')
            // ->recordUrl(null)
            // ->recordUrl(
            //     fn (accrual $record): string => Pages\EditAccrualsParkDoc::getUrl([$record->id]),
            // )
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    Action::make('edit')
                        ->label('Add Park Doc')
                        ->icon('heroicon-o-document-text')
                        ->url(fn ($record) => AccrualsResource::getUrl('edit-parkdoc', ['record' => $record->id]))
                        ->visible(fn ($record) => $record->UCR_Park_Doc == null),
                    // ->visible(fn() => HasPermissions ('update-accrual'))
                ])
                    ->button()
                    ->label('Actions')
                    ->tooltip('Actions')
                    ->size(ActionSize::ExtraSmall)
                    ->icon('heroicon-m-ellipsis-horizontal'),
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
                InfolistSection::make('Accruals Details')
                    ->icon('heroicon-m-banknotes')
                    ->schema([
                        TextEntry::make('ucr_ref_id')
                            ->label('UCR Reference ID')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-clipboard')
                            ->iconPosition(IconPosition::After)
                            ->copyable()
                            ->copyMessage('Copied!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('client_name')
                            ->label('Client'),
                        TextEntry::make('person_in_charge')
                            ->icon('heroicon-o-user')
                            ->label('Person-in-charge'),
                        TextEntry::make('wbs_no')
                            ->label('WBS No.'),
                        TextEntry::make('accrual_amount')
                            ->label('Accrual Amount')
                            ->money('Php'),
                        TextEntry::make('contract_type')
                            ->label('Contract Type')
                            ->icon('heroicon-o-document-check'),
                        TextEntry::make('business_unit')
                            ->label('Business Unit')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Facility Services' => 'gray',
                                'Transport Services' => 'warning',
                                'Warehouse Services' => 'success',
                                'General Services' => 'info',
                                'Cons & Reno Services' => 'primary',
                            }),
                        TextEntry::make('period_started')
                            ->label('Period started')
                            ->date()
                            ->icon('heroicon-o-calendar-days'),
                        TextEntry::make('period_ended')
                            ->label('Period ended')
                            ->icon('heroicon-o-calendar-days'),
                        TextEntry::make('month')
                            ->label('Month')
                            ->icon('heroicon-o-calendar'),
                        TextEntry::make('UCR_Park_Doc')
                            ->label('UCR Park Doc No.'),
                        TextEntry::make('date_accrued')
                            ->label('Date Accrued in SAP')
                            ->date()
                            ->icon('heroicon-o-calendar-days'),
                        TextEntry::make('particulars')
                            ->label('Particulars')
                            ->lineClamp(2)
                            ->columnSpanFull(),
                        TextEntry::make('accruals_attachment')
                            ->label('Attachments')
                            ->columnSpanFull(),
                    ])->columnspan([
                        'default' => 2,
                        'sm' => 3,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 5,
                        '2xl' => 6,
                    ])
                    ->columns([
                        'default' => 2,
                        'sm' => 3,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 5,
                        '2xl' => 6,
                    ]),

                // Fieldset::make('Date created and updated')
                //     ->schema([
                //         TextEntry::make('created_at')
                //             ->icon('heroicon-o-calendar')
                //             ->dateTime(),
                //         TextEntry::make('updated_at')
                //             ->icon('heroicon-o-calendar')
                //             ->dateTime(),
                //     ])->columnspan(1)->columns(1),
            ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccruals::route('/'),
            'create' => Pages\CreateAccruals::route('/create'),
            'edit' => Pages\EditAccruals::route('/{record}/edit'),
            'edit-parkdoc' => Pages\EditAccrualsParkDoc::route('/{record}/edit-parkdoc'),
            'view' => Pages\ViewAccruals::route('/{record}/view'),

        ];
    }

    public static function getWidgets(): array
    {
        return [

            AccrualStats::class,

        ];
    }
}
