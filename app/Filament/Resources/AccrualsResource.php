<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Accrual;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AccrualsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AccrualsResource\RelationManagers;
use App\Filament\Resources\AccrualsResource\Pages\UpdateAccruals;
use Livewire\Component;
use NumberFormatter;

class AccrualsResource extends Resource
{
    protected static ?string $model = Accrual::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('ucr_ref_id')
                    ->default(function() {
                        $lastUcrRefId = Accrual::orderBy('ucr_ref_id', 'desc')->first()?->ucr_ref_id;
                        if ($lastUcrRefId) {
                            // Extract the numeric part (assuming format "UCR-CE-xxxxxx")
                            $lastNumber = (int) Str::afterLast($lastUcrRefId, '-');
                            return 'UCR-CE-' . str_pad(++ $lastNumber, 7, '0', STR_PAD_LEFT);
                        } else {
                            // Handle the case where no UCR reference IDs exist yet
                            // (Consider a default starting point or user input)
                            return 'UCR-CE-0000001'; // Example default (adjust as needed)
                        }
                    })
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(32)
                    ->unique(Accrual::class, 'ucr_ref_id', ignoreRecord: true)
                    ->label('UCR Reference ID')
                    ->placeholder('UCR Reference ID'),
                TextInput::make('client_name')
                    ->label('Client')
                    ->placeholder('Client')
                    ->maxLength(32)
                    ->columnSpan('full'),
                TextInput::make('person_in_charge')
                    ->label('Person-in-charge')
                    ->maxLength(32)
                    ->placeholder('Person-in-charge'),
                TextInput::make('wbs_no')
                    ->label('WBS No.')
                    ->maxLength(32)
                    ->placeholder('WBS No.'),
                TextInput::make('particulars')
                    ->label('Particulars')
                    ->maxLength(50)
                    ->placeholder('Particulars')
                    ->columnSpanFull(),
                ])->columnspan(2)
                  ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('period_covered')
                            ->label('Period Covered'),
                        Select::make('month')
                            ->label('Month')
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
                        Select::make('business_unit')
                                ->label('Business Unit')
                                ->options([
                                    'Facility Services' => 'Facility Services',
                                    'Transport Services' => 'Transport Services',
                                    'Warehouse Services' => 'Warehouse Services',
                                    'General Services' => 'General Services',
                                    'Cons & Reno Services' => 'Cons & Reno Services',
                                ]),
                        Select::make('contract_type')
                                ->label('Contract Type')
                                ->options([
                                    'LCSP' => 'LCSP',
                                    'OOS' => 'OOS',
                                ]),
                    ])->columnspan(1),
                Section::make('')
                        ->schema([
                            TextInput::make('accrual_amount')
                                ->label('Accrual Amount')
                                ->prefix('₱')
                                ->numeric()
                                ->minValue(1)
                                ->inputMode('decimal')
                                ->placeholder('Accrual Amount'),

                        FileUpload::make('accruals_attachment')
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
                        ])->columnspan(2),
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
                TextColumn::make('ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('parkdocument.date_accrued')
                //     ->label('Date Accrued in SAP')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('parkdocument.UCR_Park_Doc')
                //     ->label('UCR Park Document No.')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('business_unit')
                    ->label('Business Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accrual_amount')
                    ->label('Accrual Amount')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period_covered')
                    ->label('Period Covered')
                    ->searchable()
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
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                        //->label('Add UCR No.'),

                ]),
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
            'index' => Pages\ListAccruals::route('/'),
            'create' => Pages\CreateAccruals::route('/create'),
            'edit' => Pages\EditAccruals::route('/{record}/edit'),

        ];
    }
}
