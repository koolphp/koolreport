<?php
/**
 * This file is the view of table widget 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */
	use \koolreport\core\Utility;
	$tableCss = Utility::get($this->cssClass,"table");
	$trClass = Utility::get($this->cssClass,"tr");
	$tdClass = Utility::get($this->cssClass,"td");
	$thClass = Utility::get($this->cssClass,"th");
	$tfClass = Utility::get($this->cssClass,"tf");
?>
<div class="koolphp-table" id="<?php echo $this->name; ?>">
	<table<?php echo ($tableCss)?" class='table $tableCss'":" class='table'"; ?>>
		<?php
		if($this->showHeader)
		{
		?>
		<thead>
			<?php
			foreach($this->headers as $header)
			{
			?>
			<tr>
				<?php
				foreach($header as $hName=>$hValue)
				{
					?>
					<th<?php
					foreach($hValue as $k=>$v)
					{
						if($k!="label")
						{
							echo " $k='$v'";
						}
					}
					?>><?php echo Utility::get($hValue,"label",$hName); ?></th>
					<?php
				}
				?>
			</tr>
			<?php	
			}
			?>
			<tr>
			<?php 
			foreach($showColumnKeys as $cKey)
			{
				$label = Utility::get($meta["columns"][$cKey],"label",$cKey);
				$cssStyle = Utility::get($meta["columns"][$cKey],"cssStyle",null);
				$thStyle = is_string($cssStyle)?$cssStyle:Utility::get($cssStyle,"th");
				$class = "";
				if($thClass)
				{
					$class = (gettype($thClass)=="string")?$thClass:$thClass($cKey);
				}
				echo "<th".(($thStyle)?" style='$thStyle'":"").(($class!="")?" class='$class'":"").">$label</th>";
			}
			?>
			</tr>
		</thead>
		<?php	
		}
		?>
		<?php
		if($this->showFooter!==null)
		{
		?>
		<tfoot <?php echo ($this->showFooter==="top")?"style='display:table-row-group'":""; ?>>
			<tr>
			<?php
			foreach($showColumnKeys as $cKey)
			{
				$cssStyle = Utility::get($meta["columns"][$cKey],"cssStyle",null);
				$tfStyle = is_string($cssStyle)?$cssStyle:Utility::get($cssStyle,"tf");
			?>
				<td <?php if($tfClass){echo " class='".((gettype($tfClass)=="string")?$tfClass:$tfClass($cKey))."'";} ?> <?php echo ($tfStyle)?"style='$tfStyle'":""; ?> >
					<?php 
						$footerValue = isset($this->footer[$cKey])?$this->formatValue($this->footer[$cKey],$meta["columns"][$cKey]):"";
						$footerText = Utility::get($meta["columns"][$cKey],"footerText");
						if($footerText!==null)
						{
							echo str_replace("@value",$footerValue,$footerText);
						}
						else
						{
							echo $footerValue;
						}
					?>
				</td>	
			<?php	
			}
			?>
			</tr>
		</tfoot>
		<?php	
		}
		?>
		<tbody>
			<?php
			$this->dataStore->popStart(); 
			while($row=$this->dataStore->pop())
			{
				$i=$this->dataStore->getPopIndex();
				$rowStyle = "";
				if($this->paging)
				{
					if($i<$this->paging["pageIndex"]*$this->paging["pageSize"] || $i>=($this->paging["pageIndex"]+1)*$this->paging["pageSize"])
					{
						$rowStyle.="display:none;";
					}
				}			
			?>
			<tr row-index='<?php echo $i; ?>'<?php echo ($rowStyle!="")?" style='$rowStyle'":""; ?><?php if($trClass){echo " class='".((gettype($trClass)=="string")?$trClass:$trClass($row))."'";} ?>>
				<?php
				foreach($showColumnKeys as $cKey)
				{
					$cssStyle = Utility::get($meta["columns"][$cKey],"cssStyle",null);
					$tdStyle = is_string($cssStyle)?$cssStyle:Utility::get($cssStyle,"td");

					if($span && isset($span[$i][$cKey]))
					{
						if($span[$i][$cKey]>0)
						{
							?>
								<td row-value="<?php echo ($cKey!=="#")?$row[$cKey]:($i+$meta["columns"][$cKey]["start"]);?>" <?php echo ($tdStyle)?"style='$tdStyle'":""; ?> <?php if($tdClass){echo "class='".((gettype($tdClass)=="string")?$tdClass:$tdClass($row,$cKey))."'";} ?> rowspan="<?php echo $span[$i][$cKey]; ?>">
									<?php echo $this->formatValue(($cKey!=="#")?$row[$cKey]:($i+$meta["columns"][$cKey]["start"]),$meta["columns"][$cKey],$row);?>
								</td>
							<?php						
						}
					}	
					else
					{
						?>
							<td row-value="<?php echo ($cKey!=="#")?$row[$cKey]:($i+$meta["columns"][$cKey]["start"]);?>" <?php echo ($tdStyle)?"style='$tdStyle'":""; ?> <?php if($tdClass){echo " class='".((gettype($tdClass)=="string")?$tdClass:$tdClass($row,$cKey))."'";} ?>>
								<?php echo $this->formatValue(($cKey!=="#")?$row[$cKey]:($i+$meta["columns"][$cKey]["start"]),$meta["columns"][$cKey],$row);?>
							</td>
						<?php					
					}								
				}						
				?>
			</tr>
			<?php	
			}
			?>
			<?php
			if($this->dataStore->countData()<=0)
			{
			?>
				<tr><td colspan="<?php echo count($showColumnKeys); ?>" align="center"><?php echo $this->translate("No data available in table"); ?></td></tr>
			<?php	
			}
			?>
		</tbody>
	</table>
	<?php
	if($this->paging)
	{
	?>
	<div style='text-align:<?php echo $this->paging["align"]; ?>'>
		<nav></nav>
	</div>
	<?php	
	}
	?>
</div>
<script type="text/javascript">
	var <?php echo $this->name; ?> = new KoolPHPTable('<?php echo $this->name; ?>',<?php echo json_encode(array(
	"paging"=>$this->paging
	)); ?>);
	<?php
	if($this->clientEvents)
	{
		foreach($this->clientEvents as $eventName=>$function)
		{
		?>
		<?php echo $this->name; ?>.on("<?php echo $eventName; ?>",<?php echo $function; ?>);
		<?php	
		}
	}
	?>	
</script>