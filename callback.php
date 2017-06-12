<?php
include 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

//https://api.twitch.tv/kraken/oauth2/authorize?response_type=code&client_id=nv14nio5bm8w0harprb4sfsoaw2ycex&redirect_uri=http%3A%2F%2Flaravel.aaa%2Fsubs&scope=user_read+user_subscriptions

//https://www.reddit.com/api/v1/authorize?client_id=CLIENT_ID&response_type=TYPE&state=RANDOM_STRING&redirect_uri=URI&duration=DURATION&scope=SCOPE_STRING
//https://www.reddit.com/api/v1/authorize?client_id=E-s3Sgyo6rBC9Q&response_type=code&state=RANDOM_STRING&redirect_uri=http%3A%2F%2Fmarefx.science%2reddit%2callback.php&duration=temporary&scope=history
function get_data($url, $auth)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $auth;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "web:personal.reddit.test.app:v0.1 (by /u/mareftw)");
    $server_output = curl_exec($ch);
    curl_close($ch);
    return $server_output;
}

function get_token($auth)
{
    $ch = curl_init("https://www.reddit.com/api/v1/access_token");
    $headers = array();
    $headers[] = 'Authorization: Basic ' . base64_encode("E-s3Sgyo6rBC9Q:TcpFzXgG-LrUSwcqcjq9n3fFIuI");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    $fields = array(
        'grant_type' => 'authorization_code',
        'code' => $auth,
        'redirect_uri' => 'https://marefx.com/reddit_sorter/callback.php'
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_USERAGENT, "web:personal.reddit.test.app:v0.2 (by /u/mareftw)");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $response = json_decode($data, true);
    $access_token = $response['access_token'];
    return $access_token;
}

//15903244-fd6msBlWFZVohXakXKOm6V22S1s
if (isset($_GET['code']) && !empty($_GET['code'])) {
    //echo 'Logged in<br>';
    $code = $_GET['code'];
    $token = "6K7GeJ_s0Qz3J1FYMk4R8-hEQ3s";
    //$token = get_token($code); //works -- need to save token for 1h, cookie? //use refresh
    //echo "Access token: " . $token;

    //&before=t3_3mbmn1
    //&before=t3_3vhbro

    $links_json = get_data("https://oauth.reddit.com/user/mareftw/upvoted?limit=100&before=t3_56ghc0", $token);
    $links_decoded = json_decode($links_json);

    checkLinks($links_decoded, $token);

    print_r($links_json);

} else {
    $rand = substr(md5(microtime()), rand(0, 5), 20);
    echo "<a href='https://www.reddit.com/api/v1/authorize?client_id=E-s3Sgyo6rBC9Q&response_type=code&state=" . $rand . "&redirect_uri=https://marefx.com/reddit_sorter/callback.php&duration=temporary&scope=history+identity'>Login</a>";
}

// 948 => 1
// 1048 => 948
// 1148 => 1048
// 1173 => 1148
// od starejsih proti novim ??
// 1174 => 1273
// 1274 => 1373
// 1374 => 1381 (1374 = last upvoted | t3_4e5uj7)
// 1382 => 1481
// 1482 => 1567 (1482 = last upvoted | t3_56ghc0)




function checkLinks($urlData, $token)
{
    if (!empty($urlData->data->after)) {
        foreach ($urlData->data->children as $link) {
            $preview = !empty($link->data->preview) ? $link->data->preview->images[0]->source->url : null;
            $info = array("mareftw", $link->data->author, $link->data->url, $link->data->title, $link->data->ups, $link->data->created, $link->data->permalink, $link->data->selftext,
                $link->data->selftext_html, $preview, $link->data->subreddit, $link->data->name);
            //echo $link->data->url . "<br>";
            //echo $link->data->preview->images[0]->source->url . "<br>";
            insertDB($info);

        }
        $links_json = get_data("https://oauth.reddit.com/user/mareftw/upvoted?limit=100&after=" . $urlData->data->after, $token);
        $links_decoded = json_decode($links_json);
        checkLinks($links_decoded, $token);
    } else {
        foreach ($urlData->data->children as $link) {
            $preview = !empty($link->data->preview) ? $link->data->preview->images[0]->source->url : null;
            $info = array("mareftw", $link->data->author, $link->data->url, $link->data->title, $link->data->ups, $link->data->created, $link->data->permalink, $link->data->selftext,
                $link->data->selftext_html, $preview, $link->data->subreddit, $link->data->name);
            //echo $link->data->url . "<br>";
            //echo $link->data->preview->images[0]->source->url . "<br>";
            insertDB($info);
        }
    }
}

function getLastTName()
{
    global $mysqli;
    $request = $mysqli->prepare("SELECT * FROM user_upvoted ORDER BY id DESC LIMIT 1");
    $request->execute();
    //$results = $request->fetchAll(PDO::FETCH_OBJ);
    //return $results;
    $results = $request->rowCount();
    $fetch = $request->fetchAll(PDO::FETCH_OBJ);
    if ($results == 0) {
        return false;
    } else {
        return $fetch->nameT;
    }
}

function existsDB($values)
{
    global $mysqli;
    $request = $mysqli->prepare("SELECT * FROM user_upvoted WHERE subreddit = :subreddit AND author = :author AND created = :created");
    $request->execute(array(
        'subreddit' => $values[10],
        'author' => $values[1],
        'created' => $values[5]
    ));
    //$results = $request->fetchAll(PDO::FETCH_OBJ);
    //return $results;
    $results = $request->rowCount();
    if ($results == 0) {
        return false;
    } else {
        return true;
    }
}

function insertDB($values)
{
    global $mysqli;
    $request = $mysqli->prepare("INSERT INTO user_upvoted(username,author,url,title,upvotes,created,permalink,selftext,selftext_html,preview_img,subreddit,nameT,added_on) VALUES (:username,:author,:url,:title,:upvotes,:created,:permalink,:selftext,:selftext_html,:preview_img,:subreddit,:nameT,NOW())");
    $request->execute(array(
        "username" => $values[0],
        "author" => $values[1],
        "url" => $values[2],
        "title" => $values[3],
        "upvotes" => $values[4],
        "created" => $values[5],
        "permalink" => $values[6],
        "selftext" => $values[7],
        "selftext_html" => $values[8],
        "preview_img" => $values[9],
        "subreddit" => $values[10],
        "nameT" => $values[11]
    ));
    if ($request->rowCount() > 1) {
        return true;
    } else {
        return false;
    }
}