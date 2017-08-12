var myApp = angular.module('myApp', []);
myApp.controller('loginController', ['$scope', '$location', function ($scope, $location) {
    $scope.listUer = [];
    $scope.userObj = {};
    firebase.auth().onAuthStateChanged(firebaseUser => {
        if (firebaseUser) {
            $scope.$apply(function () {
                $.bootstrapGrowl("<b>Đang chuyển trang....</b>", {
                    ele: 'body', // which element to append to
                    type: 'info', // (null, 'info', 'danger', 'success', 'warning')
                    offset: {
                        from: 'top',
                        amount: 10
                    }, // 'top', or 'bottom'
                    align: 'center', // ('left', 'right', or 'center')
                    width: 300, // (integer, or 'auto')
                    delay: 5000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
                    allow_dismiss: true, // If true then will display a cross to close the popup.
                    stackup_spacing: 10 // spacing between consecutively stacked growls.
                });
                window.location.href = "./index.html";
            });
        } else {
            console.log('not logged');
        }
    });
    //$scope.userloged = "";
    $scope.register = function () {
        var res = firebase.auth().createUserWithEmailAndPassword($scope.reg_user_id, $scope.reg_user_pwd);
        res.then(function () {
            var userid = firebase.auth().currentUser.uid;
            console.log(userid);
            const dbUser = firebase.database().ref('user/' + userid).set({
                email: firebase.auth().currentUser.email,
                level: "member",
                message: {
                    "123456": {
                        'content': 'Chào mừng tới xuanphuong.xyz',
                        'isRead': 0
                    }
                }
            });
            $.bootstrapGrowl("<b>Thông báo: </b>Đăng ký thành công", {
                ele: 'body', // which element to append to
                type: 'success', // (null, 'info', 'danger', 'success', 'warning')
                offset: {
                    from: 'top',
                    amount: 10
                }, // 'top', or 'bottom'
                align: 'right', // ('left', 'right', or 'center')
                width: 300, // (integer, or 'auto')
                delay: 5000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
                allow_dismiss: true, // If true then will display a cross to close the popup.
                stackup_spacing: 10 // spacing between consecutively stacked growls.
            });
        }).catch(function (e) {
            $.bootstrapGrowl("<b>Lỗi: </b>" + e.message, {
                ele: 'body', // which element to append to
                type: 'danger', // (null, 'info', 'danger', 'success', 'warning')
                offset: {
                    from: 'top',
                    amount: 10
                }, // 'top', or 'bottom'
                align: 'center', // ('left', 'right', or 'center')
                width: 300, // (integer, or 'auto')
                delay: 5000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
                allow_dismiss: true, // If true then will display a cross to close the popup.
                stackup_spacing: 10 // spacing between consecutively stacked growls.
            });
        });
    };
    $scope.login = function () {
        var res = firebase.auth().signInWithEmailAndPassword($scope.user_id, $scope.user_pwd);
        res.then(function () {
            $scope.$apply(function () {
                $.bootstrapGrowl("<b>Thông báo: </b>Đăng nhập thành công", {
                    ele: 'body', // which element to append to
                    type: 'success', // (null, 'info', 'danger', 'success', 'warning')
                    offset: {
                        from: 'top',
                        amount: 10
                    }, // 'top', or 'bottom'
                    align: 'right', // ('left', 'right', or 'center')
                    width: 300, // (integer, or 'auto')
                    delay: 5000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
                    allow_dismiss: true, // If true then will display a cross to close the popup.
                    stackup_spacing: 10 // spacing between consecutively stacked growls.
                });
            });
        }).catch(function (e) {
            $.bootstrapGrowl("<b>Lỗi: </b>" + e.message, {
                ele: 'body', // which element to append to
                type: 'danger', // (null, 'info', 'danger', 'success', 'warning')
                offset: {
                    from: 'top',
                    amount: 10
                }, // 'top', or 'bottom'
                align: 'center', // ('left', 'right', or 'center')
                width: 300, // (integer, or 'auto')
                delay: 5000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
                allow_dismiss: true, // If true then will display a cross to close the popup.
                stackup_spacing: 10 // spacing between consecutively stacked growls.
            });
        });
    };
    $scope.validatePassword = function () {
        if ($scope.reg_user_pwd != $scope.regre_user_pwd && $scope.reg_user_pwd != '' && $scope.regre_user_pwd != '') {
            return true;
        }
        return false;
    }
    $scope.notify = function () {
        $.bootstrapGrowl("<b>Thông báo: </b>Đăng nhập thành công", {
            ele: 'body', // which element to append to
            type: 'success', // (null, 'info', 'danger', 'success', 'warning')
            offset: {
                from: 'top',
                amount: 10
            }, // 'top', or 'bottom'
            align: 'right', // ('left', 'right', or 'center')
            width: 300, // (integer, or 'auto')
            delay: 5000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
            allow_dismiss: true, // If true then will display a cross to close the popup.
            stackup_spacing: 10 // spacing between consecutively stacked growls.
        });
    }
}]);