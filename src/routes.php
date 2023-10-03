<?php

/**
 * Loads the route specified by the URL.
 *
 * @param string $route The URL route
 */

function load_route(string $route)
{
    $routes = array(
        // Home
        '/' => ABSPATH . 'Home/Home.php',
        '/privacy-policy' => ABSPATH . 'Home/PrivacyPolicy.php',
        '/terms-of-service' => ABSPATH . 'Home/TermsOfService.php',
        '/what-is-slo-shogi' => ABSPATH . 'Home/WhatIsSloShogi.php',
        // Auth
        '/login' => ABSPATH . 'Auth/Login.php',
        '/verify-login' => ABSPATH . 'Auth/VerifyLogin.php',
        '/forgot-password' => ABSPATH . 'Auth/ForgotPassword.php',
        '/email-temp-password' => ABSPATH . 'Auth/EmailTempPassword.php',
        '/new-account' => ABSPATH . 'Auth/NewAccount.php',
        '/verify-creation' => ABSPATH . 'Auth/VerifyCreation.php',
        '/account-setup' => ABSPATH . 'Auth/AccountSetup.php',
        '/reset-password' => ABSPATH . 'Auth/ResetPassword.php',
        '/finalize-account' => ABSPATH . 'Auth/FinalizeAccount.php',
        '/logout' => ABSPATH . 'Auth/Logout.php',
        '/user-page' => ABSPATH . 'User/UserPage.php',
        // Settings
        '/settings' => ABSPATH . 'Settings/Settings.php',
        '/settings/update-icon' => ABSPATH . 'Settings/UpdateIcon.php',
        '/settings/set-icon' => ABSPATH . 'Settings/UpdateIcon.php',
        '/settings/change-koma-set' => ABSPATH . 'Settings/ChangeKomaSet.php',
        '/settings/preview-koma-set' => ABSPATH . 'Settings/PreviewKomaSet.php',
        '/settings/submit-comp' => ABSPATH . 'Settings/PreviewKomaSet.php', // missing file
        // Gameboard
        '/gameboard' => ABSPATH . 'Gameboard/Gameboard.php',
        '/gameboard/move-reservation' => ABSPATH . 'Gameboard/MoveReservation.php',
        '/gameboard/kifu/write-kifu' => ABSPATH . 'Gameboard/WriteKifu.php',
        '/gameboard/send-chat' => ABSPATH . 'Gameboard/SendChat.php',
        // New Game
        '/new-game' => ABSPATH . 'NewGame/NewGame.php',
        '/new-game/new-challenge' => ABSPATH . 'NewGame/NewChallenge.php',
        '/new-game/create-open-game' => ABSPATH . 'NewGame/CreateOpenGame.php',
        '/new-game/new-open-game' => ABSPATH . 'NewGame/NewOpenGame.php',
        '/new-game/join-game' => ABSPATH . 'NewGame/JoinGame.php',
        '/decline-challenge' => ABSPATH . 'NewGame/DeclineChallenge.php',
        '/accept-challenge' => ABSPATH . 'NewGame/AcceptChallenge.php',
        '/private-game' => ABSPATH . 'NewGame/PrivateGame.php',
        // Friends
        '/friends' => ABSPATH . 'Friends/Friends.php',
        '/friends/add-friends' => ABSPATH . 'Friends/AddFriends.php',
        '/friends/invite-email' => ABSPATH . 'Friends/InviteEmail.php',
        '/friends/view-friend' => ABSPATH . 'Friends/ViewFriend.php',
        '/friends/add-to-friends' => ABSPATH . 'Friends/AddToFriends.php',
        '/friends/send-invite' => ABSPATH . 'Friends/SendInvite.php',
        // Forum
        '/forum' => ABSPATH . 'Forum/Forum.php',
        '/forum/forum-top' => ABSPATH . 'Forum/ForumTop.php',
        '/forum/create-topic' => ABSPATH . 'Forum/CreateTopic.php',
        '/forum/category' => ABSPATH . 'Forum/Category.php',
        '/forum/topic' => ABSPATH . 'Forum/Topic.php',
        // SloTsumeshogi
        '/slotsumeshogi' => ABSPATH . 'SloTsumeshogi/SloTsumeshogi.php',
        '/slotsumeshogi/publish-tsume' => ABSPATH . 'SloTsumeshogi/PublishTsume.php',
        '/slotsumeshogi/tsume' => ABSPATH . 'SloTsumeshogi/Tsume.php',
        '/slotsumeshogi/make-account-tsume' => ABSPATH . 'SloTsumeshogi/MakeAccountTsume.php',
        '/slotsumeshogi/set-tsume' => ABSPATH . 'SloTsumeshogi/SetTsume.php',
        '/slotsumeshogi/all-tsume' => ABSPATH . 'SloTsumeshogi/AllTsume.php',
        '/slotsumeshogi/all-tsume/tsume' => ABSPATH . 'SloTsumeshogi/Tsume.php',
        '/slotsumeshogi/pro-tsume' => ABSPATH . 'SloTsumeshogi/ProTsume.php',
        '/slotsumeshogi/my-tsume' => ABSPATH . 'SloTsumeshogi/MyTsume.php',
        '/slotsumeshogi/initialize-tsume' => ABSPATH . 'SloTsumeshogi/InitializeTsume.php',
        '/slotsumeshogi/edit-tsume' => ABSPATH . 'SloTsumeshogi/EditTsume.php',
        // Feedback
        '/feedback-form' => ABSPATH . 'Feedback/FeedbackForm.php',
        '/send-feedback' => ABSPATH . 'Feedback/SendFeedback.php',
    );

    function routeExist(string $route, array $routes)
    {
        foreach ($routes as $key => $value) {
            if ($route == $key) {
                return true;
            }
        }
    }

    if (routeExist($route, $routes) && file_exists($routes[$route])) {
        require_once $routes[$route];
    } else {
        // send 404
        header("HTTP/1.0 404 Not Found");
    }
}
