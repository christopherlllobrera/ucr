<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Panel;
use Filament\Tables;
use NumberFormatter;
use App\Models\accrual;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasPermissions;
use App\Filament\Resources\AccrualsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AccrualsResource\RelationManagers;
use App\Filament\Resources\AccrualsResource\Pages\UpdateAccruals;

use App\Filament\Resources\AccrualsResource\Widgets\AccrualStats;
use App\Filament\Resources\AccrualsResource\Pages\EditAccrualsParkDoc;


class AccrualsResource extends Resource
{
    protected static ?string $model = accrual::class;
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
                    ->default(function() {
                        $lastUcrRefId = accrual::orderBy('ucr_ref_id', 'desc')->first()?->ucr_ref_id;
                        if ($lastUcrRefId) {
                            // Extract the numeric part (assuming format "UCR-CE-xxxxxx")
                            $lastNumber = (int) Str::afterLast($lastUcrRefId, '-');
                            return 'UCR-CE-' . str_pad(++ $lastNumber, 6, '0', STR_PAD_LEFT);
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
                        return $leftCharacters . ' characters';
                    }),
                    TextInput::make('accrual_amount')
                                ->label('Accrual Amount')
                                ->prefix('₱')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->inputMode('decimal')
                                ->disabledOn(Pages\EditAccrualsParkDoc::class)
                                ->placeholder('Accrual Amount')
                                ->columnSpanFull(),
                        FileUpload::make('accruals_attachment')
                                ->label('Attachments')
                                ->multiple()
                                ->required()
                                ->minFiles(0)
                                ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf' ,'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
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
                                // #IMAGE Settings
                                // ->image()
                                // ->imageEditor()
                                // ->imageResizeMode('force')
                                // ->imageCropAspectRatio('8:5')
                                // ->imageResizeTargetWidth('1920')
                                // ->imageResizeTargetHeight('1080')
                                // ->imageEditorViewportWidth('1920')
                                // ->imageEditorViewportHeight('1080'),
                                ->columnSpanFull(),
                ])->columnspan(2)
                  ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('period_started')
                            ->label('Period started')
                            ->required()
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
                            ->required()
                            ->hiddenOn([Pages\EditAccruals::class, Pages\CreateAccruals::class])
                    ])->columnspan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('No Accruals yet')
            ->emptyStateDescription('Once you create your first accrual, it will appear here.')
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
            ->recordUrl(
                fn (accrual $record): string => Pages\EditAccrualsParkDoc::getUrl([$record->id]),
            )
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('edit')
                        ->label('Add Park Doc')
                        ->icon('heroicon-o-document-text')
                        ->url(fn ($record) => AccrualsResource::getUrl('edit-parkdoc', ['record' => $record->id]))
                        ->visible(fn ($record) => $record->UCR_Park_Doc == null),
                        // ->visible(fn() => HasPermissions ('update-accrual'))
                ]),
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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccruals::route('/'),
            'create' => Pages\CreateAccruals::route('/create'),
            'edit' => Pages\EditAccruals::route('/{record}/edit'),
            'edit-parkdoc' => Pages\EditAccrualsParkDoc::route('/{record}/edit-parkdoc')

        ];
    }

    public static function getWidgets(): array
    {
        return [

            AccrualStats::class,

        ];
    }
}
