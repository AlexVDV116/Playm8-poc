<?php
class Account {

    private string $accountID;
    private string $email;
    private string $password;
    private bool $enabled;
    private bool $isLoggedIn;
    private array $roles;
    private userProfile $userProfile;

    function __construct($email, $password, $enabled, $isLoggedIn) {
        $this->accountID = uniqid("AID");
        $this->email = $email;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->roles = array();
        $this->isLoggedIn = $isLoggedIn;
    }

    public function createUserProfile($firstName, $location, $phoneNumber, $dateOfBirth) {
        $userProfileID = "UP" . substr($this->accountID, 3);
        $this->userProfile = new userProfile($userProfileID, $firstName, $location, $phoneNumber, $dateOfBirth);
    }

    public function getAccountID() : string 
    {
        return $this->accountID;
    }

    public function logIn() : void {
        $this->isLoggedIn = true;
    }

    public function logOut() : void {
        $this->isLoggedIn = false;
    }
    
    public function addRole(Role $role) : void
    {
        array_push($this->roles, $role);
    }

    public function delRole(Role $role) : void 
    {
        unset($this->roles[array_search($role, $this->roles)]); 
    }

    public function showRole() : array
    {
        return $this->roles;
    }

    public function checkPermission(Permission $permission) : bool
    {
        $found = false;
        foreach($this->roles as $role) {
            if($role->checkPermission($permission)) {
                $found = true;
                break;
            }
        }

        if ($found === false) {
            return false;
        } else {
            return true;
        }
    }
}

class userProfile {
    private string $userProfileID;
    private string $firstName;
    private string $location;
    private string $phoneNumber;
    private string $dateOfBirth;

    public function __construct($userProfileID, $firstName, $location, $phoneNumber, $dateOfBirth) 
    {
        $this->userProfileID = $userProfileID;
        $this->firstName = $firstName;
        $this->location = $location;
        $this->phoneNumber = $phoneNumber;
        $this->dateOfBirth = $dateOfBirth;
    }
}

class roleManager {
    public function giveRole(Account $account, Role $role) : void
     {
        $account->addRole($role);
    }   

    public function remRole(Account $account, Role $role) : void 
    {
        $account->delRole($role);
    }

    public function showRoles(Account $account) : array 
    {
       return $account->showRole();
    }

    public function givePermission(Role $role, Permission $permission) : void
    {
        $role->addPermission($permission);
    }

    public function remPermission(Role $role, Permission $permission) : void 
    {
        $role->delPermission($permission);
    }

    public function showPermissions(Role $role) : array 
    {
        return $role->showPermission();
    }

    public function checkPermissions(Account $account, Permission $permission)
    {
        return $account->checkPermission($permission);
    }
}

class Role {
    private string $roleName;
    private string $roleDescription;
    private array $permissions;

    public function __construct($roleName, $roleDescription) {
        $this->roleName = $roleName;
        $this->roleDescription = $roleDescription;
        $this->permissions = array();
    }

    public function __toString() : string 
    {
        return $this->roleName;
    }

    public function addPermission(Permission $permission) : void 
    {
        array_push($this->permissions, $permission);
    }

    public function delPermission(Permission $permission) : void 
    {
        unset($this->permissions[array_search($permission, $this->permissions)]); 
    }

    public function showPermission() : array 
    {
        return $this->permissions;
    }
    
    public function checkPermission(Permission $permission) : bool
    {
        return in_array($permission, $this->permissions);
    }
}

class Permission {
    private string $permissionName;
    private string $permissionDescription;

    public function __construct($permissionName, $permissionDescription) {
        $this->permissionName = $permissionName;
        $this->permissionDescription = $permissionDescription;
    }

}


// -------------------------------------------------------------- PROOF OF CONCEPT --------------------------------------------------------------



// Instantiation of an account object
$test_account = new Account("test@email.com", "supersecretpassword01!", true, false);
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
$test_account->createUserProfile("Alex", "51.58404459919641, 4.797649863611824", "+31637293365", "10/07/1991");
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

// roleManager showRoles method on the test_account
echo "roleManager showRoles on test_account:";
echo "<pre>";
var_dump($role_manager->showRoles($test_account));
echo "</pre>";

// Instantiation of the del_acc_permission object and the roleManager object giving the permission to the admin role
$del_acc_permission = new Permission("Delete account", "Permission to delete an account");
$role_manager->givePermission($admin, $del_acc_permission);
echo nl2br("Instantiation of the del_acc_permission object &\r\nThe roleManager giving the permission to the admin role:");
echo "<pre>";
var_dump($admin);
echo "</pre>";

// roleManager object showing the permissions of the admin role
echo "roleManager showing permissions of admin role:";
echo "<pre>";
var_dump($role_manager->showPermissions($admin));
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
var_dump($role_manager->checkPermissions($test_account, $del_acc_permission));
echo "</pre>";

// roleManager removing the del_acc_permission from the admin role
$role_manager->remPermission($admin, $del_acc_permission);
echo "RoleManager removing del_acc_permission permission from admin role:";
echo "<pre>";
var_dump($admin);
echo "</pre>";

// roleManager checking for permission again
echo "roleManager checking for permission again";
echo "<pre>";
var_dump($role_manager->checkPermissions($test_account, $del_acc_permission));
echo "</pre>";

// roleManager removing the admin role form the test_account
$role_manager->remRole($test_account, $admin);
echo "RoleManager removing the admin role from test_account:";
echo "<pre>";
var_dump($test_account);
echo "</pre>";

?>