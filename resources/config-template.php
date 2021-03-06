<?php
/**
 * scimify
 * Author: Dragos Gaftoneanu <dragos.gaftoneanu@okta.com>
 * 
 * Disclaimer: This SCIM server was built in order to simulate and troubleshoot different SCIM use-cases and not to be used in production. The script is provided AS IS 
 * without warranty of any kind. Okta disclaims all implied warranties including, without limitation, any implied warranties of fitness for a particular purpose. We highly
 * recommend testing scripts in a preview environment if possible.
 */

/* MySQL configuration */
define("SCIMIFY_DB_USERNAME", "root");
define("SCIMIFY_DB_PASSWORD", "");
define("SCIMIFY_DB_SERVER", "localhost");
define("SCIMIFY_DB_NAME", "scimify");

/* Basic Authentication */
define("ENABLE_BASIC_AUTH", true);
define("BASIC_AUTH_USER", "scimadmin");
define("BASIC_AUTH_PASS", "{SSHA256}Hi4JU996$4ea9ee4ae4ce0291324e9e027630f09798d24e891f87a226903113e9cee5359"); // "admin"

/* OAuth Bearerer Authentication - client credentials to get token */
define("ENABLE_BEARER_AUTH", false);
define("OAUTH_CLIENT_ID", "PT5wnwy17c0s6K90");
define("OAUTH_CLIENT_SECRET", "Hk7T2anTdy44jn4dJe5IEyS7RW1adt4V");

/* Other configuration */
error_reporting(0);
