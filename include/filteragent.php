<div class="form-group">
    <select class="form-control" name="agent">
        <option value="">Agent</option>
        <?php 
        // Get agents (users where utype is 'agent')
        $agentQuery = mysqli_query($con, "SELECT uname FROM user WHERE utype = 'agent' ORDER BY uname ASC");
                                                        
        if (!$agentQuery) {
            echo "<!-- Error: " . mysqli_error($con) . " -->";
        } else {
            while($agentRow = mysqli_fetch_array($agentQuery)) {
                // Check if this option should be selected
                $selected = isset($_GET['agent']) && $_GET['agent'] == $agentRow['uname'] ? 'selected' : '';
            
                echo '<option value="'.htmlspecialchars($agentRow['uname']).'" '.$selected.'>'.htmlspecialchars($agentRow['uname']).'</option>';
            }
        }
        ?>
    </select>
</div>