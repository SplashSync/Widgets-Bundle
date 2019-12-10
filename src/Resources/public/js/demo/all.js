
/*
 * This file is part of SplashSync Project.
 *
 * Copyright (C) 2015-2019 Splash Sync  <www.splashsync.com>
 * All rights reserved.
 *
 * NOTE: All information contained herein is, and remains the property of Splash
 * Sync and its suppliers, if any.  The intellectual and technical concepts
 * contained herein are proprietary to Splash Sync and its suppliers, and are
 * protected by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior written
 * permission is obtained from Splash Sync.
 *
 * @author Bernard Paquier <contact@splashsync.com>
 */

//------------------------------------------------------------------------------
// LOAD|BUILD ALL JS & CSS for DEMO
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Jquery & Bootstrap 4
import './1-bootstrap4.js';
//------------------------------------------------------------------------------
// SB ADMIN THEME
import './2-theme.js';
//------------------------------------------------------------------------------
// Fontawesome 5 Icons
import './3-fontawesome.js';

//------------------------------------------------------------------------------
// Theme Demo Font
import './../../scss/fonts.scss';

//------------------------------------------------------------------------------
// Include Charts Libraries
import './../1-sparkline.js';
import './../2-morris.js';

//------------------------------------------------------------------------------
// Splash Widgets JS Code
import './../SplashWidgets.js';

console.log("DEMO: Webpack Done!");
        