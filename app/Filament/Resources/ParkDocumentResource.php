<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\accrual;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\parkdocument;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ParkDocumentResource\Pages;
use App\Filament\Resources\ParkDocumentResource\RelationManagers;


class ParkDocumentResource extends Resource
{
    protected static ?string $model = parkdocument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'UCR Park Document';
    protected static ?string $breadcrumb = 'UCR Park Documents';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'UCR Park Document';
    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Add UCR Park Document')
                ->description('Please select UCR Reference ID and fill out the UCR Park Doc. below.')
                ->schema([
                    Select::make('ucr_ref_id')
                    ->relationship('accruals', 'ucr_ref_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->reactive()
                    ->unique(ignoreRecord:True)
                    ->label('UCR Reference ID')
                    ->afterStateUpdated(function (Get $get, Set $set,){
                        $accrual = $get('ucr_ref_id');
                        $accrual = accrual::find($accrual);
                        $set('client_name', $accrual->client_name);
                        $set('person_in_charge', $accrual->person_in_charge);
                        $set('wbs_no', $accrual->wbs_no);
                        $set('particulars', $accrual->particulars);
                        $set('period_covered', $accrual->period_covered);
                        $set('month', $accrual->month);
                        $set('business_unit', $accrual->business_unit);
                        $set('contract_type', $accrual->contract_type);
                        $set('accrual_amount', $accrual->accrual_amount);
                        $set('accruals_attachment', $accrual->accruals_attachment);
                    })
                    ->AfterStateHydrated(function (Get $get, Set $set,){
                        if ($get('ucr_ref_id')) {
                            $accrual = accrual::find($get('ucr_ref_id'));
                            $set('client_name', $accrual->client_name);
                            $set('person_in_charge', $accrual->person_in_charge);
                            $set('wbs_no', $accrual->wbs_no);
                            $set('particulars', $accrual->particulars);
                            $set('period_covered', $accrual->period_covered);
                            $set('month', $accrual->month);
                            $set('business_unit', $accrual->business_unit);
                            $set('contract_type', $accrual->contract_type);
                            $set('accrual_amount', $accrual->accrual_amount);
                            $set('accruals_attachment', $accrual->accruals_attachment);
                        }
                    }),
                TextInput::make('client_name')
                    ->label('Client')
                    //->placeholder('Client')
                    ->reactive()
                    ->columnSpan('full')
                    ->readOnly(),
                TextInput::make('person_in_charge')
                    ->label('Person-in-charge')
                    ->maxLength(32)
                    //->placeholder('Person-in-charge')
                    ->readOnly(),
                TextInput::make('wbs_no')
                    ->label('WBS No.')
                    ->maxLength(32)
                    //->placeholder('WBS No.')
                    ->readOnly(),
                TextInput::make('particulars')
                    ->label('Particulars')
                    ->maxLength(50)
                    //->placeholder('Particulars')
                    ->columnSpanFull()
                    ->readOnly(),
                ])->columnspan(2)
                  ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('period_covered')
                            ->label('Period Covered')
                            ->readOnly(),
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
                            ])
                            ->disabled(),
                        TextInput::make('business_unit')
                                ->label('Business Unit')
                                //->placeholder('Business Unit')
                                ->readOnly(),
                        Select::make('contract_type')
                                ->label('Contract Type')
                                ->options([
                                    'LCSP' => 'LCSP',
                                    'OOS' => 'OOS',
                                ])
                                ->disabled(),
                    ])->columnspan(1),
                Section::make('')
                        ->schema([
                            TextInput::make('accrual_amount')
                                ->label('Accrual Amount')
                                ->prefix('₱')
                                ->numeric()
                                ->minValue(1)
                                ->readOnly()
                                //->placeholder('Accrual Amount')
                                ->inputMode('decimal'),
                                // FileUpload::make('accruals_attachment')
                                // ->label('Attachments')
                                // ->deletable(true)
                                // ->multiple()
                                // ->minFiles(0)
                                // ->reorderable()
                                // ->acceptedFileTypes(['image/*', 'application/vnd.ms-excel', 'application/pdf' ,'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                                // //Storage Setting
                                // ->preserveFilenames()
                                // ->previewable()
                                // ->maxSize(100000) //100MB
                                // ->disk('local')
                                // ->directory('Invoice_Attachments')
                                // ->visibility('public')
                                // ->downloadable()
                                // ->openable()
                                // #IMAGE Settings
                                // ->image()
                                // ->imageEditor()
                                // ->imageResizeMode('force')
                                // ->imageCropAspectRatio('8:5')
                                // ->imageResizeTargetWidth('1920')
                                // ->imageResizeTargetHeight('1080')
                                // ->imageEditorViewportWidth('1920')
                                // ->imageEditorViewportHeight('1080')
                                //,
                        ])->columnspan(2),
                    Section::make()
                        ->schema([
                        TextInput::make('UCR_Park_Doc')
                            ->label('UCR Park Document No.')
                            ->placeholder('UCR Park Document No.')
                            ->unique(ignoreRecord:True)
                            ->required(),
                            //->disabledOn('create'),
                            //->hiddenOn('create'),
                        DatePicker::make('date_accrued')
                            ->label('Date Accrued in SAP')
                            //->hiddenOn('create'),
                        ])->columnspan(1)->columns(1)//->hiddenOn('create'),
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
                TextColumn::make('accruals.client_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_accrued')
                    ->label('Date Accrued in SAP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('UCR_Park_Doc')
                    ->label('UCR Park Document No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.business_unit')
                    ->label('Business Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.accrual_amount')
                    ->label('Accrual Amount')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('accruals.period_covered')
                    ->label('Period Covered')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('accruals.month')
                    ->label('Month')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('accruals.wbs_no')
                    ->label('WBS No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('accruals.contract_type')
                    ->label('Contract Type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()

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
            'index' => Pages\ListParkDocuments::route('/'),
            'create' => Pages\CreateParkDocument::route('/create'),
            'edit' => Pages\EditParkDocument::route('/{record}/edit'),
        ];
    }

}

