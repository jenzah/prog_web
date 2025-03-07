<div class="form-group">
    <select class="form-control" name="type">
        <option value="">Catégorie</option>
        <?php 
        // Get unique property types
        $typeQuery = mysqli_query($con, "SELECT DISTINCT propertyType FROM property ORDER BY propertyType ASC");

        if (!$typeQuery) {
            echo "<!-- Error: " . mysqli_error($con) . " -->";
        } else {
            while($typeRow = mysqli_fetch_array($typeQuery)) {
                // Check if this option should be selected (if it matches the current GET parameter)
                $selected = isset($_GET['type']) && $_GET['type'] == $typeRow['propertyType'] ? 'selected' : '';

                echo '<option value="'.htmlspecialchars($typeRow['propertyType']).'" '.$selected.'>'.htmlspecialchars(ucwords(strtolower($typeRow['propertyType']))).'</option>';
            }
        }
        ?>
    </select>
</div>