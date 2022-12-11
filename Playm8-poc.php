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
    public function getAccount($accountID) {
        //continue;// Een functie die de juiste object vindt adhv de accountID?
    }

    public function giveRole($account, $role) {
        $account->addRole($role);
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

$test_account = new Account("test@email.com", "supersecretpassword01!", true, false);

echo "<pre>";
var_dump($test_account);
$test_account->logIn();
var_dump($test_account);
echo "</pre>";


$test_account->createUserProfile("Alex", "51.58404459919641, 4.797649863611824", "+31637293365", "10/07/1991");

echo "<pre>";
$test_account->logOut();
var_dump($test_account);


$role_manager = new roleManager();
$admin = new Role("admin", "administrator description");
$role_manager->giveRole($test_account, $admin);

var_dump($test_account);
echo "</pre>";
?>