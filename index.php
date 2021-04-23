<?php

use Kl\User;
use Kl\UserPaymentsService;

require_once 'vendor/autoload.php';

$user = new User(1,105.2 , "testing@test.mail");
$user2 = new User(2,99.9 , "testovich@proviralyovich.mail");
$userPayService = new UserPaymentsService();

$amount = -20;
$amount2 = 100500;
try {
    $expectedBalance = ($user->balance + $amount);
    if($userPayService->changeBalance($user, $amount)){
        $info = sprintf('User balance should be updated %s: %s <br />', $expectedBalance, $user->balance);
        $result = true;
    }
    else{
        $info = sprintf('User balance shouldn`t be updated %s: %s <br />', $expectedBalance, $user->balance);
        $result = false;
    }
} catch (Exception $e) {
    $result = false;

    $info = sprintf('User balance shouldn`t be updated, exception: %s <br />', $e->getMessage());
}

echo sprintf("[%s] %s\n", $result ? 'SUCCESS' : 'FAIL', $info);

try {
    $expectedBalance = ($user2->balance + $amount2);
    if($userPayService->changeBalance($user2, $amount2)){
        $info = sprintf('User balance should be updated %s: %s <br />', $expectedBalance, $user2->balance);
        $result = true;
    }
    else{
        $info = sprintf('User balance shouldn`t be updated %s: %s <br />', $expectedBalance, $user2->balance);
        $result = false;
    }
} catch (Exception $e) {
    $result = false;

    $info = sprintf('User balance shouldn`t be updated, exception: %s <br />', $e->getMessage());
}

echo sprintf("[%s] %s\n", $result ? 'SUCCESS' : 'FAIL', $info);

$users = $userPayService->getUserDbTable()->getUsers();
$payments = $userPayService->getUserPaymentsDbTable()->getPayments();

?>
<!--
таблица для удобного отображения результата
-->
<br /><hr />

<table border="1" cellspacing="0" width="100%" style="margin: 50px 0 50px 0" >
    <tr >
        <td colspan="3">
            <div style="text-align: center; color: blue;">User DB</div>
        </td>
    </tr>
    <tr>
        <th>
            ID
        </th>
        <th>
            Email
        </th>
        <th>
            Balance
        </th>
    </tr>
    <?php foreach ($users as $profile){
        echo "<tr><td>" . $profile['id'] . "</td><td>" . $profile['email'] . "</td><td>" . $profile['balance'] . "</td></tr>";
    }?>
</table>

<br />

<table border="1" cellspacing="0" width="100%">
    <tr >
        <td colspan="5">
            <div style="text-align: center; color: tomato;">Payment DB</div>
        </td>
    </tr>
    <tr>
        <th>
            ID
        </th>
        <th>
            UserID
        </th>
        <th>
            Type
        </th>
        <th>
            Balance Before
        </th>
        <th>
            Amount
        </th>
    </tr>
    <?php foreach ($payments as $pay){
        echo "<tr><td>" . $pay['id'] . "</td><td>" . $pay['userid'] . "</td><td>" . $pay['type'] . "</td><td>" . $pay['balancebefore'] . "</td><td>" . $pay['amount'] . "</td></tr>";
    }?>
</table>

