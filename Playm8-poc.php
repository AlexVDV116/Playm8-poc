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

    public function getAccountID() {
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
    public function giveRole($account, $role) {
        $account->addRole($role);
    }   

    public function remRole($account, $role) {
        $account->delRole($role);
    }

    public function showRoles($account) {
       return $account->showRole();
    }
}

class Role {
    private string $roleName;
    private string $roleDescription;

    public function __construct($roleName, $roleDescription) {
        $this->roleName = $roleName;
        $this->roleDescription = $roleDescription;
    }

    public function __toString(){
        return $this->roleName;
    }
}

class Permission {
    private string $permissionName;

    public function __construct($permissionName) {
        $this->permissionName = $permissionName;
    }

}

// --------------------------------------------------------------POC --------------------------------------------------------------

$test_account = new Account("test@email.com", "supersecretpassword01!", true, false);

echo "Instantiation of account object:";
echo "<pre>";
var_dump($test_account);
echo "</pre>";

echo "Logged in account:";
echo "<pre>";
$test_account->logIn();
var_dump($test_account);
echo "</pre>";


$test_account->createUserProfile("Alex", "51.58404459919641, 4.797649863611824", "+31637293365", "10/07/1991");

echo "Instantiation of user profile object in account object:";
echo "<pre>";
$test_account->logOut();
var_dump($test_account);
echo "</pre>";


$role_manager = new roleManager();
$admin = new Role("admin", "administrator description");
$role_manager->giveRole($test_account, $admin);

echo nl2br("Instantiation of the roleManager and admin role objects\r\nThe roleManager giving the admin role to the test account");
echo "<pre>";
var_dump($test_account);
echo "</pre>";

echo "roleManager showRoles on test_account:";
echo "<pre>";
var_dump($role_manager->showRoles($test_account));
echo "</pre>";

$role_manager->remRole($test_account, $admin);

echo "RoleManager removing admin role from test_account";
echo "<pre>";
var_dump($test_account);
echo "</pre>";

?>