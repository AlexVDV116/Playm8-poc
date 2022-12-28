<?php
class Account
{

    private string $accountID;
    private string $account_username;
    private string $email;
    private string $password;
    private bool $enabled;
    private array $roles;
    private userProfile $userProfile;

    public function __construct($account_username, $email, $password, $enabled)
    {
        $this->accountID = uniqid("AID");
        $this->account_username = $account_username;
        $this->email = $email;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->roles = array();
    }

    public function createUserProfile($firstName, $lastName, $location, $phoneNumber, $dateOfBirth, $age)
    {
        $userProfileID = "UP" . substr($this->accountID, 3);
        $this->userProfile = new userProfile($userProfileID, $firstName, $lastName, $location, $phoneNumber, $dateOfBirth, $age);
    }

    public function getAccountID(): string
    {
        return $this->accountID;
    }

    public function logIn(): void
    {
        $this->isLoggedIn = true;
    }

    public function logOut(): void
    {
        $this->isLoggedIn = false;
    }

    public function addRole(Role $role): void
    {
        array_push($this->roles, $role);
    }

    public function deleteRole(Role $role): void
    {
        unset($this->roles[array_search($role, $this->roles)]);
    }

    public function getRole(): array
    {
        return $this->roles;
    }

    public function hasPermission(Permission $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
}

class userProfile
{
    private string $userProfileID;
    private string $firstName;
    private string $lastName;
    private string $location;
    private string $phoneNumber;
    private string $dateOfBirth;
    private string $age;

    public function __construct($userProfileID, $firstName,  $lastName, $location, $phoneNumber, $dateOfBirth, $age)
    {
        $this->userProfileID = $userProfileID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->location = $location;
        $this->phoneNumber = $phoneNumber;
        $this->dateOfBirth = $dateOfBirth;
        $this->age = $age;
    }
}

class roleManager
{
    public function giveRole(Account $account, Role $role): void
    {
        $account->addRole($role);
    }

    public function removeRole(Account $account, Role $role): void
    {
        $account->deleteRole($role);
    }

    public function getRoles(Account $account): array
    {
        return $account->getRole();
    }

    public function givePermission(Role $role, Permission $permission): void
    {
        $role->addPermission($permission);
    }

    public function removePermission(Role $role, Permission $permission): void
    {
        $role->deletePermission($permission);
    }

    public function getPermissions(Role $role): array
    {
        return $role->getPermission();
    }

    public function hasPermissions(Account $account, Permission $permission)
    {
        return $account->hasPermission($permission);
    }
}

class Role
{
    private string $roleName;
    private string $roleDescription;
    private array $permissions;

    public function __construct($roleName, $roleDescription)
    {
        $this->roleName = $roleName;
        $this->roleDescription = $roleDescription;
        $this->permissions = array();
    }

    public function __toString(): string
    {
        return $this->roleName;
    }

    public function addPermission(Permission $permission): void
    {
        $this->permissions[] = $permission;
    }

    public function deletePermission(Permission $permission): void
    {
        unset($this->permissions[array_search($permission, $this->permissions)]);
    }

    public function getPermission(): array
    {
        return $this->permissions;
    }

    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, $this->permissions);
    }
}

class Permission
{
    private string $permissionName;
    private string $permissionDescription;

    public function __construct($permissionName, $permissionDescription)
    {
        $this->permissionName = $permissionName;
        $this->permissionDescription = $permissionDescription;
    }
}


// -------------------------------------------------------------- PROOF OF CONCEPT --------------------------------------------------------------



// Instantiation of an account object
$test_account = new Account("testaccount", "test@email.com", "supersecretpassword01!", false);
echo "Instantiation of an account object:";
echo "<pre>";
var_dump($test_account);
echo "</pre>";

// Logging in account
echo "Logged in account:";
echo "<pre>";
$test_account->logIn();
var_dump($test_account);
echo "</pre>";

// Instantiation of user profile object inside the account object
$test_account->createUserProfile("Alex", "Test", "51.58404459919641, 4.797649863611824", "+31637293365", "10/07/1991", "31");
echo "Instantiation of user profile object inside the account object:";
echo "<pre>";
$test_account->logOut();
var_dump($test_account);
echo "</pre>";

// Instantiation of the roleManager object and admin Role object and the roleManager object giving the admin role to the test account
$role_manager = new roleManager();
$admin = new Role("admin", "administrator description");
$role_manager->giveRole($test_account, $admin);
echo nl2br("Instantiation of the roleManager and admin role objects &\r\nThe roleManager giving the admin role to the test account:");
echo "<pre>";
var_dump($test_account);
echo "</pre>";

// roleManager getRoles method on the test_account
echo "roleManager getRoles on test_account:";
echo "<pre>";
var_dump($role_manager->getRoles($test_account));
echo "</pre>";

// Instantiation of the del_acc_permission object and the roleManager object giving the permission to the admin role
$del_acc_permission = new Permission("Delete account", "Permission to delete an account");
$role_manager->givePermission($admin, $del_acc_permission);
echo nl2br("Instantiation of the del_acc_permission object &\r\nThe roleManager giving the permission to the admin role:");
echo "<pre>";
var_dump($admin);
echo "</pre>";

// roleManager object getting the permissions of the admin role
echo "roleManager getting permissions of admin role:";
echo "<pre>";
var_dump($role_manager->getPermissions($admin));
echo "</pre>";

// test_account with the admin role and del_account_permission
echo "test_account with the admin role and del_account_permission:";
echo "<pre>";
var_dump($test_account);
echo "</pre>";

// roleManager object checking test_account for del_acc_permission (loops trough the roles array
//and checks if permission exists in a role, returns true or false)
echo "roleManager object checking test_account for del_acc_permission (loops trough the roles array and checks if permission exists in a role, ";
echo "returns true or false):";
echo "<pre>";
var_dump($role_manager->hasPermissions($test_account, $del_acc_permission));
echo "</pre>";

// roleManager removing the del_acc_permission from the admin role
$role_manager->removePermission($admin, $del_acc_permission);
echo "RoleManager removing del_acc_permission permission from admin role:";
echo "<pre>";
var_dump($admin);
echo "</pre>";

// roleManager checking for permission again
echo "roleManager checking for permission again";
echo "<pre>";
var_dump($role_manager->hasPermissions($test_account, $del_acc_permission));
echo "</pre>";

// roleManager removing the admin role form the test_account
$role_manager->removeRole($test_account, $admin);
echo "RoleManager removing the admin role from test_account:";
echo "<pre>";
var_dump($test_account);
echo "</pre>";
