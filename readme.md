### Package to manage user roles for Laravel application

Having to write code for managing user roles each time you create a new Laravel project is  a repeating project. Sometimes, you will try to copy the existing code form the other projects, such as Role model class.
You will need to create a model class for role, plus, migration file for it. Then you also have to define the relationship between user and role. Then you will have to write methods for related logic for example, checking if the user belongs to a role. This package includes all the necessary common logic and components fo handling user roles for Laravel application.


#### installation

- `composer require waiyanhein/lara-role-manager`

#### Publishing config file to define roles

-  `php artisan vendor:publish`

Then roles.php file will be published under the app/config folder. You can define the roles in the following format.

`
[
   1 => 'Superadmin',
   2 => 'Manager',
   3 => 'Staff'
]
`
The array key is the unique code and the value is the title to be displayed to the end users.

#### Migrating the table
- Then you migrate the tables for the roles. `php artisan migrate`

#### Usages

You will need to place the `Waiyanhein\LaraRoleManager\Traits\RoleManager` trait into the user model class.

#### Seeding data (Optional)
If you are seeding the roles into the database, you can seed the data from the `config/roles.php` calling the following method in your seeder class.
- `\Waiyanhein\LaraRoleManager\LaraRolesSeeder::seed();`

#### Methods
Then you can use the following methods based on your need.

`Waiyanhein\LaraRoleManager\Models\Role` class

- `findRole($code)` - static method. This method will return the role based on the code. $code is the unique code defined in the `config/roles.php` file mapped to the displayed title.
- `users()` - This method is the Eloquent relationship. You can leverage all the features of Eloquent on this method. For example, `$role->users()->get()`.
- `roles()` - static method. This method will return all the roles in the `id` => `displayed title` mapping. Return type is array.

`Waiyanhein\LaraRoleManager\Traits\RoleManager` trait

You embed this trait into your user model class so that your user model class can leverage all the features of this trait.

- `roles()` - This method is the Eloquent relationship. You can leverage all the features of Eloquent on this method. For example: `$user->roles()->get()`.
- `attachRole($code)` - This will attach a role to the user. $code is the unique code defined in the `config/roles.php` file mapped to the displayed title. For example: `$user->attachRole(User::ROLE_ADMIN)`;
- `attachRoles($codes)` - This is similar to `attachRole` function. Instead, you pass an array of codes to assign multiple roles to the user at the same time.
- `hasRole($code)` - Checks if the user has a role. This method will return boolean value. For example: `$user->hasRole(User::ROLE_ADMIN)`.
- `hasRoles($codes)`. This method is very similar to `hasRole` method. Instead, this will check if the user belongs to all the roles which are pass as an array to the method. If any of the role is missing, it will return false.
