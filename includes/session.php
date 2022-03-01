<?php
date_default_timezone_set("America/Chicago");
//before we store information of our member, we need to start first the session
session_start();

function is_logged_in()
{
	return isset($_SESSION['user']);
}

function is_admin()
{
	return $_SESSION['user']['permissions'] >= 2;
}

// CURRENTLY USING A DIFFERENT VERSION OF THE FUNCTION, CAN EASILY SWITCH BACK IF M.A. WANTS TO RESTRICT WHO CAN ACCESS WHICH PROPERTY
function can_access($property_id)
{
	// return is_admin() || in_array($site_id, $_SESSION['properties']);
	// return true;
	return in_array($property_id, $_SESSION['properties']);
}
