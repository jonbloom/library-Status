<?php

	#
	# Connect to database
	#

	$db = new mysqli('localhost', 'root', 'root', 'library_status');

	if ($db->connect_errno) {
    	printf("Connect failed: %s\n", $db->connect_error);
    	exit();
	}

	date_default_timezone_set('America/Detroit');


	$issue_id = $_POST['issue_id'];
	$system_name = $_POST['system_name'];


	$issue_id = 55;
	$system_name = 'Library Homepage';

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>GVSU University Libraries Status</title>

	<link rel="stylesheet" type="text/css" href="resources/css/styles.css"/>
	<link rel="stylesheet" type="text/css" href="http://gvsu.edu/cms3/assets/741ECAAE-BD54-A816-71DAF591D1D7955C/libui.css" />
	<link rel="stylesheet" type="text/css" href="resources/css/layout.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
	<div class="line break" style="padding-top: 1em">
		<div class="span1 unit">
			<h2>University Libraries Status</h2>
		</div> <!-- end span -->
	</div> <!-- end line -->

	<div class="line">
		<div class="span1 unit">

		<!-- Create issues -->
		<?php

				$result = $db->query("SELECT s.status_id, s.issue_id, s.status_timestamp, s.status_public, s.status_user_id, s.status_text, s.status_delete, u.user_id, u.user_fn, u.user_ln, st.status_type_id, st.status_type_text
					FROM status_entries s, user u, status_type st
					WHERE s.issue_id = $issue_id AND s.status_user_id = u.user_id AND s.status_type_id = st.status_type_id
					ORDER BY s.status_timestamp ASC");

				$num_rows = $result->num_rows;
				$issue_id = $status_entries['issue_id'];

				$rc = 0;

				// display issues and check for comments
				while ($status_entries = $result->fetch_assoc()) {

					$rc++;

					// first post
					if ($rc == 1) {

						echo '
						<!-- Issue -->
						<div class = "line">
							<div class="issue-box">
								<div class="span1 unit issue">
									<div style="float: left;">
										<p class="name">' . $status_entries['user_fn'] . " " . $status_entries['user_ln'] .'</p>
										<p class="time">' . date("D g:i a - n/j/y", $status_entries['status_timestamp']) . '</p>
									</div>
									<div style="float: right;">
										<p class="name tag-system">' . $system_name . '</p>';
										
										if ($status_entries['status_type_text'] == 'Outage') {
											echo '<p class="name tag-outage">' . $status_entries['status_type_text'] . '</p>';
										} else if ($status_entries['status_type_text'] == 'Disruption') {
											echo '<p class="name tag-disruption">' . $status_entries['status_type_text'] . '</p>';
										} else {
											echo '<p class="name tag-resolution">' . $status_entries['status_type_text'] . '</p>';
										}

										echo '
									</div>
									<p class="comment-text">' . $status_entries['status_text'] . '</p>
								</div> <!-- end span --> ';

					// list comments
					} else if ($num_rows >= 2) {
							echo '
								<div class="span1 unit comment-box">
									<div style="float: left;">
										<p class="name">' . $status_entries['user_fn'] . " " . $status_entries['user_ln'] .'</p>
										<p class="time">' . date("D g:i a - n/j/y", $status_entries["status_timestamp"]) . '</p>
									</div>
									<p class ="comment-text">' . $status_entries['status_text'] . '</p>
								</div> <!-- end span --> ';


					}

				} // close status loop

				echo '</div></div> <!-- close issue line -->';

		?>
	
		</div> <!-- end span -->
	</div> <!-- end line -->

	<div class="line break footer">
		<div class="span1 unit break">
			<p>Footer - Grand Valley State University Libraries</p>
		</div> <!-- end span -->
	</div> <!-- end line -->
</body>

</html>

