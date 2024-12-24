<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            Learning Platform
        </a>
        <button
            class="navbar-toggler"
            data-toggle="collapse"
            data-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navbarMenu" class="navbar-collapse collapse">
            <div class="nav-left">
                <ul class="nav-pages">
                    <li>
                        <a href="<?php echo BASE_URL; ?>courses">
                            My courses
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>purchases">
                            Purchases
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>donation">
                            Donate
                        </a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav nav-right">
                <a href="<?php echo BASE_URL; ?>cart" style="cursor: pointer; -webkit-padding-end: 20px;">
                    <div class="cart">
                        <div class="notification_icon">
                            <span id="notifications_new" class="notifications_new badge badge-danger badge-pill">
                                <?php echo $cartItemsCount; ?>
                            </span>
                            <i style="font-size:24px" class="fa">&#xf07a;</i>
                        </div>
                    </div>
                </a>

                <?php $this->loadView("notification/NotificationView", array("notifications" => $notifications)); ?>

                <div class="dropdown">
                    <a class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                        <?php echo $username; ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-item">
                            <a href="<?php echo BASE_URL; ?>settings">
                                Profile
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <a href="<?php echo BASE_URL; ?>settings/edit">
                                Edit profile
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li class="dropdown-item">
                            <a href="<?php echo BASE_URL; ?>support">
                                Support
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li class="dropdown-item">
                            <a href="<?php echo BASE_URL; ?>home/logout">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>