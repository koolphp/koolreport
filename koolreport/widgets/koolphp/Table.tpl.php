<?php
/**
 * This file is the view of table widget 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */
	use \koolreport\core\Utility;
	$tableCss = Utility::get($this->cssClass,"table");
	$trClass = Utility::get($this->cssClass,"tr");
	$tdClass = Utility::get($this->cssClass,"td");
?>
<table <?php echo ($tableCss)?" class='table $tableCss'":"class='table'"; ?>>
	<thead>
		<tr>
		<?php 
		foreach($showColumnKeys as $cKey)
		{
		    $label = Utility::get($meta["columns"][$cKey],"label",$cKey);
			echo "<th>$label</th>";
		}
		?>
		</tr>
	</thead>
	<tbody>
		<?php
        $this->dataStore->popStart(); 
		while($row=$this->dataStore->pop())
		{
			$i=$this->dataStore->getPopIndex();
		?>
		<tr<?php if($trClass){echo " class='".((gettype($trClass)=="string")?$trClass:$trClass($row))."'";} ?>>
			<?php
			foreach($showColumnKeys as $cKey)
			{
					if($span && isset($span[$i][$cKey]))
					{
						if($span[$i][$cKey]>0)
						{
							?>
								<td<?php if($tdClass){echo "class='".((gettype($tdClass)=="string")?$tdClass:$tdClass($row,$cKey))."'";} ?> rowspan="<?php echo $span[$i][$cKey]; ?>"><?php echo Utility::format($row[$cKey],$meta["columns"][$cKey]); ?></td>
							<?php						
						}
					}	
					else
					{
						?>
							<td<?php if($tdClass){echo " class='".((gettype($tdClass)=="string")?$tdClass:$tdClass($row,$cKey))."'";} ?>><?php echo Utility::format($row[$cKey],$meta["columns"][$cKey]);?></td>
						<?php					
					}								
			}						
			?>
		</tr>
		<?php	
		}
		?>
	</tbody>
</table>