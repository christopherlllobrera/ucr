<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Permission;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    //->placeholder('First Name, middle Initial , last Name')
                    ,
                    TextInput::make('email')
                    ->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('password')
                    ->password()->required()
                    ->dehydrated(fn($state)=> Hash::make($state))
                    ->required()
                    ->minLength(8)
                    ->revealable()
                    ->maxLength(255),

                ]) ->columns(2),

                Section::make([
                    Select::make('Roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
                    Select::make('Permission')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload(),
                ]) ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                        ->label('Name')
                        ->icon('heroicon-m-user')
                        ->searchable(),
                        TextColumn::make('email')
                        ->icon('heroicon-m-envelope')
                        ->label('Email')
                        ->searchable(),
                        TextColumn::make('roles.name')
                        ->label('Roles')
                        ->icon('heroicon-m-shield-check')
                        ->searchable(),
                Panel::make([
                    Split::make([
                        TextColumn::make('permissions.name')
                        ->icon('heroicon-m-key')
                        ->label('Permissions')
                        ->searchable(),
                    ])
                ])->collapsed(true)

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
            ->defaultPaginationPageOption(25);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}/view'),
        ];
    }
}
