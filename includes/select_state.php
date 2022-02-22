<?php
$states_json = fopen("states.json", "r", true);
$states = json_decode(stream_get_contents($states_json), true);
echo "<select name='state' class='form-select'>";

foreach ($states as $state) {
	if (isset($priority) && ($state == $priority || array_search($state, $states) == $priority)) {
		echo $state;
		echo "<option selected value=" . array_search($state, $states) . ">" . $state . "</option>";
	} else {
		echo "<option value=" . array_search($state, $states) . ">" . $state . "</option>";
	}
}

echo "</select>";
