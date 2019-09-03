<div style="text-align: center;">
    <title>更改密碼|儀管系統</title>
    <h2>NTUH.YL 醫療儀器管理系統</h2>
    <h3>更改登入密碼</h3>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src='https://www.google.com/recaptcha/api.js?render=6LeBabYUAAAAANSlx-Nbk08n6oXp8dnwlP4_FZ9K'></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6LeBabYUAAAAANSlx-Nbk08n6oXp8dnwlP4_FZ9K', {action: 'homepage'}).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>
    <?php
    // View
    require("config.php");
    require("request.php");

    $tmppw = request("tmppw");
    $acc = request("acc");
    $input_acc = request("input_acc");
    $input_old_pw = request("input_old_pw");
    $input_new_pw = request("input_new_pw");
    $input_new_pw_re = request("input_new_pw_re");
    $redirection_ok = request("redirection_ok");

    if ($redirection_ok == "true" && $acc != "") {
        echo '<span style="color: blue; "><b>成功:已重新申請更改密碼</b></span><br>員工編號(帳號):' . $acc . '<br>請至Email信箱使用更改密碼連結重新更改';
    } else {
        if ($input_new_pw == "") {
            if ($tmppw != "" && $acc != "") {
                ?>
                <form action="" method="post">
                    &emsp;員工編號：<input type="text" name="input_acc" required placeholder="請輸入員工編號(帳號)"
                                      value=<?php echo $acc; ?> disabled="disabled"><br><br>
                    &emsp;臨時密碼：<input type="text" name="input_tmppw" required placeholder="tmppw_**********"
                                      value=<?php echo $tmppw; ?> disabled="disabled"><br><br>
                    &emsp;&emsp;新密碼：<input type="password" name="input_new_pw" required placeholder="請設定新密碼"><br><br>
                    確認新密碼：<input type="password" name="input_new_pw_re" required placeholder="請再次輸入新密碼"><br><br>
                    <input type="submit" value="確認更改">
                    <input type="hidden" value="" name="recaptcha_response" id="recaptchaResponse">
                </form>
                <?php
            } else {
                ?>
                <form action="" method="post">
                    &emsp;員工編號：<input type="text" name="input_acc" required placeholder="請輸入員工編號(帳號)"><br><br>
                    &emsp;&emsp;原密碼：<input type="text" name="input_old_pw" required placeholder="請輸入原密碼"><br><br>
                    &emsp;&emsp;新密碼：<input type="password" name="input_new_pw" required placeholder="請設定新密碼"><br><br>
                    確認新密碼：<input type="password" name="input_new_pw_re" required placeholder="請再次輸入新密碼"><br><br>
                    <input type="submit" value="確認更改">
                    <input type="hidden" value="" name="recaptcha_response" id="recaptchaResponse">
                </form>
                <?php
            }
            echo '<span style="color: red; "><b>注意:新密碼不允許以"tmppw_"為開頭</b></span>';
        } else {
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = '6LeBabYUAAAAAAMOXXfKUNUQ2T6ZHLHGd-D9KgmE';
            $recaptcha_response = $_POST['recaptcha_response'];
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);
            if ($recaptcha->success == true) {
                if ($recaptcha->score >= 0.5) {
                    if ($tmppw != "") {
                        $sql = 'SELECT application_time FROM `user_tmppw_tb` WHERE `account`=:acc and `tmppw`=:tmppw order by application_time desc';
                        $rs = $db->prepare($sql);
                        $rs->bindValue(':acc', $acc, PDO::PARAM_STR);
                        $rs->bindValue(':tmppw', $tmppw, PDO::PARAM_STR);
                        $rs->execute();
                        if ($rs->rowCount() == 0) {
                            echo "<span style=\"color: red; \"><b>錯誤:先前已完成修改</b></span>";
                        } else {
                            list($time) = $rs->fetch(PDO::FETCH_NUM);
                            if ((strtotime(date("Y-m-d H:i:s", time())) - strtotime($time)) <= 1800) {
                                if ($input_new_pw == $input_new_pw_re) {
                                    if (substr($input_new_pw, 0, 6) != "tmppw_") {
                                        $sql2 = 'UPDATE `user_tb` SET `password`=:input_new_pw WHERE `account`=:acc';
                                        $rs2 = $db->prepare($sql2);
                                        $rs2->bindValue(':input_new_pw', $input_new_pw, PDO::PARAM_STR);
                                        $rs2->bindValue(':acc', $acc, PDO::PARAM_STR);
                                        $rs2->execute();

                                        $sql4 = 'DELETE FROM `user_tmppw_tb`WHERE `account`=:acc';
                                        $rs4 = $db->prepare($sql4);
                                        $rs4->bindValue(':acc', $acc, PDO::PARAM_STR);
                                        $rs4->execute();

                                        echo "<span style=\"color: blue; \"><b>成功:已成功更改密碼</b></span>";
                                    } else {
                                        echo '<span style="color: red; "><b>錯誤:新密碼不允許以"tmppw_"為開頭<br>請重新輸入</b></span><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_api/change_pw.php?acc=' . $acc . '&tmppw=' . $tmppw . '">回上頁</a>';
                                    }
                                } else {
                                    echo '<span style="color: red; "><b>錯誤:新密碼與確認新密碼不相符<br>請重新輸入</b></span><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_api/change_pw.php?acc=' . $acc . '&tmppw=' . $tmppw . '">回上頁</a>';
                                }
                            } else {
                                $sql3 = 'SELECT email FROM `user_tb` WHERE `account`=:acc';
                                $rs3 = $db->prepare($sql3);
                                $rs3->bindValue(':acc', $acc, PDO::PARAM_STR);
                                $rs3->execute();
                                list($email) = $rs3->fetch(PDO::FETCH_NUM);
                                echo '<span style="color: red; "><b>錯誤:此臨時密碼已超過有效時間<br>請重新申請</b></span><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_api/user.php?mode=forget_pw&email=' . $email . '&redirection=true">重新申請</a>';
                            }
                        }
                    } else {
                        $sql5 = 'SELECT account,password FROM `user_tb` WHERE `account`=:input_acc';
                        $rs5 = $db->prepare($sql5);
                        $rs5->bindValue(':input_acc', $input_acc, PDO::PARAM_STR);
                        $rs5->execute();
                        if ($rs5->rowCount() == 0) {
                            echo '<span style="color: red; "><b>錯誤:此員工編號尚未註冊<br>請重新輸入</b></span><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_api/change_pw.php">回上頁</a>';
                        } else {
                            list($db_acc, $db_pw) = $rs5->fetch(PDO::FETCH_NUM);
                            if ($input_old_pw == $db_pw) {
                                if ($input_new_pw == $input_new_pw_re) {
                                    if (substr($input_new_pw, 0, 6) != "tmppw_") {
                                        $sql6 = 'UPDATE `user_tb` SET `password`=:input_new_pw WHERE `account`=:input_acc';
                                        $rs6 = $db->prepare($sql6);
                                        $rs6->bindValue(':input_new_pw', $input_new_pw, PDO::PARAM_STR);
                                        $rs6->bindValue(':input_acc', $input_acc, PDO::PARAM_STR);
                                        $rs6->execute();

                                        $sql7 = 'DELETE FROM `user_tmppw_tb`WHERE `account`=:input_acc';
                                        $rs7 = $db->prepare($sql7);
                                        $rs7->bindValue(':input_acc', $input_acc, PDO::PARAM_STR);
                                        $rs7->execute();

                                        echo "<span style=\"color: blue; \"><b>成功:已成功更改密碼</b></span>";
                                    } else {
                                        echo '<span style="color: red; "><b>錯誤:新密碼不允許以"tmppw_"為開頭<br>請重新輸入</b></span><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_api/change_pw.php">回上頁</a>';
                                    }
                                } else {
                                    echo '<span style="color: red; "><b>錯誤:新密碼與確認新密碼不相符<br>請重新輸入</b></span><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_api/change_pw.php">回上頁</a>';
                                }
                            } else {
                                echo '<span style="color: red; "><b>錯誤:原密碼輸入錯誤<br>請重新輸入</b></span><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_api/change_pw.php">回上頁</a>';
                            }
                        }
                    }
                } else
                    echo "<span style=\"color: red; \"><b>錯誤:驗證錯誤</b></span>";
            } else
                echo "<span style=\"color: red; \"><b>錯誤:驗證連線錯誤</b></span>";
        }
    }
    ?>
</div>