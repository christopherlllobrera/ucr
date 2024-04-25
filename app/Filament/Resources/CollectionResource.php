<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\collection;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;
use App\Models\parkdocument;
use Filament\Tables\Columns\TextColumn;

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
                Section::make()
                ->schema([
                    Select::make('ucr_ref_id')
                        ->relationship('accruals','ucr_ref_id',
                            modifyQueryUsing: function (Builder $query){
                                $query->pluck('ucr_ref_id',);
                            })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->reactive()
                        ->unique(ignoreRecord:True)
                        ->label('UCR Reference ID')
                        ->columnSpanFull(),
                    TextInput::make('amount_collected')
                        ->label('Amount Collected')
                        ->maxLength(32)
                        ->prefix('₱')
                        ->numeric()
                        ->minValue(1)
                        ->inputMode('decimal')
                        ->placeholder('Amount Collected'),
                    TextInput::make('or_number')
                        ->label('OR No.')
                        ->maxLength(32)
                        ->placeholder('OR No.'),
                    ])->columnspan(2)
                        ->columns(2),
                Section::make('')
                    ->schema([
                        DatePicker::make('tr_posting_date')
                            ->label('TR Posting Date'),
                    ])->columnspan(1),
                Section::make('')
                        ->schema([
                        FileUpload::make('collection_attachment')
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
                                ->directory('Accruals_Attachments')
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
                TextColumn::make('accruals.ucr_ref_id')
                    ->label('UCR Reference ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount_collected')
                    ->label('Amount Collected')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tr_posting_date')
                    ->label('TR Posting Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('or_number')
                    ->label('OR No.')
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
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }
}
