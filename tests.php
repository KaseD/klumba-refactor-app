<?php

use Kl\User;
use Kl\UserPaymentsService;

require_once 'vendor/autoload.php';

$userPaymentService = new UserPaymentsService();

$testData = require_once 'test-data.php';

foreach ($testData as $testDataRow) {
    list($user, $amount) = $testDataRow;

    $userModel = new User($user['id'], $user['balance'], $user['email']);

    try {
        $userPaymentService->changeBalance($userModel, $amount);

        $expectedBalance = $user['balance'] + $amount;

        $resultBalance = $userModel->balance;

        $info = sprintf('User balance should be updated %s: %s <br />', $expectedBalance, $expectedBalance);

        $result = assert($expectedBalance === $resultBalance, $info);
    } catch (Exception $e) {
        $result = false;

        $info = sprintf('User balance should be updated, exception: %s <br />', $e->getMessage());
    }

    echo sprintf("[%s] %s\n", $result ? 'SUCCESS' : 'FAIL', $info);

    $users = $userPaymentService->getUserDbTable()->getUsers();
    $payments = $userPaymentService->getUserPaymentsDbTable()->getPayments();
}?>
<!--
таблица для удобного отображения результата
-->
<br /><hr />

<table border="1" cellspacing="0" width="100%" style="margin: 50px 0 50px 0">
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
