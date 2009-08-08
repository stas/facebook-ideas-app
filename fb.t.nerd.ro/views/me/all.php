<div>
    <table id="mine_ideas">
        <thead>
            <tr>
                <td>Title for the idea</td>
                <td>Idea's rating</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($ideas as $idea) {
                    echo "<tr>";
                    echo "<td>$idea->title</td>";
                    echo "<td>$idea->rating</td>";
                    echo "<td>Read</td>";
                    echo "<td>Delete</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <?php
                    if($hasposts < 0) : // at the end
                ?>
                <td><a href="/fdbussiness/me/all/<?php echo $page - 1; ?>">&larr; Go back</a> | Page <?php echo $page; ?></a></td>
                <?php
                    elseif($hasposts == 0) : //during pagination
                ?>
                <td><a href="/fdbussiness/me/all/<?php echo $page - 1; ?>">&larr; Go back</a> | Page <?php echo $page; ?> | <a href="/fdbussiness/me/all/<?php echo $page + 1; ?>">Next page &rarr</a></td>
                   <?php
                    elseif($hasposts > 0) : //on start
                ?>
                <td>Page <?php echo $page; ?> | <a href="/fdbussiness/me/all/<?php echo $page + 1; ?>">Next page &rarr</a></td>
                
                <?php endif; ?>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
</div>