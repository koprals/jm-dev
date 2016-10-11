
<?php
  if($startDate == false || count($data) == 0) {
    ?>
    <div class="nNote nFailure">
      <p><strong>DATA IS EMPTY! please pick date</strong></p>
    </div>
    <?php
  } else {
    
    $order		=	array_keys($this->params['paging']['User']['order']);
  	$direction	=	$this->params['paging']['User']["order"][$order[0]];
  	$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction: "";

    $this->Paginator->options(array(
			'url'	=> array(
				'controller'	=> $ControllerName,
				'action'		=> 'DataValidListItem/limit:'.$viewpage,
			),
			'onclick'=>"return onClickPage(this,'#contents_area');")
		);
?>

    <div class="widget">
      <div class="title">
        <img src="<?php echo $this->webroot ?>img/icons/dark/frames.png" alt="" class="titleIcon">
        <h6>
          <?php echo Inflector::humanize("Total Data Valid")?> - Page <?php echo $this->Paginator->counter(); ?>
        </h6>
      </div>
      <table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable">
        <thead>
          <tr>
            <td style="width:5%;">
              No
            </td>
            <td style="width:10%;">
              <?php echo $this->Paginator->sort("User.code",'BA Code');?>
            </td>
            <td style="width:15%;">
              <?php echo $this->Paginator->sort("User.name",'Name');?>
            </td>
            <td style="width:10%;">
              Total Data Consumer
            </td>
            <td style="width:10%;">
              Total Email Not Valid
            </td>
            <td style="width:10%;">
              Total Email Valid
            </td>
          </tr>
        </thead>
        <tbody>
          <?php $count = 0;?>
          <?php foreach($data as $data): ?>
            <?php $count++;?>
            <?php $no		=	(($page-1)*$viewpage) + $count;?>
            <?php $class	=	($data['User']['status'] == "0") ? "style='background-color:#FFDDDE'" : "";?>
            <tr <?php echo $class?>>
              <td>
                <?php echo $no ?>
              </td>
              <td><?php echo $data['User']['code'] ?></td>
              <td><?php echo $data['User']['name'] ?></td>
              <td>
                <?php
                  $totalCustomer = 0;
                  if(isset($matixAllCustomer[$data['User']['id']])) {
                    $totalCustomer = $matixAllCustomer[$data['User']['id']];
                    echo $totalCustomer;
                  } else {
                    echo "0";
                  }
                ?>
              </td>
              <td>
                <?php
                  $totalNotValid = 0;
                  if(isset($matixAllNotValid[$data['User']['id']])) {
                    $totalNotValid = $matixAllNotValid[$data['User']['id']];
                    echo $totalNotValid;
                  } else {
                    echo "0";
                  }
                ?>
              </td>
              <td>
                <?php
                  $totalValid = 0;
                  if(isset($matixAllValid[$data['User']['id']])) {
                    $totalValid = $matixAllValid[$data['User']['id']];
                    echo $totalValid;
                  } else {
                    echo "0";
                  }
                ?>
              </td>

            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="12">
              <a href="<?php echo $settings['cms_url'].$ControllerName?>/ExcelDataValid" class="smallButton redB" title="See Child" style="padding:3px 15px 3px 15px; margin:10px; float:right; display:block;">Export Excel</a>
              <?php if($this->Paginator->hasPrev() or $this->Paginator->hasNext()):?>
              <div class="tPagination">
                <ul class="pages">
                  <?php echo $this->Paginator->prev("",
                      array(
                        "escape"	=>	false,
                        'tag'		=>	"li",
                        "class"		=>	"prev"
                      ),
                      "<a href='javascript:void(0)'></a>",
                      array(
                        'tag'		=>	"li",
                        "escape"	=>	false,
                        "class"		=>	"prev"
                      )
                    );
                  ?>

                  <?php
                    echo $this->Paginator->numbers(array(
                      'separator'		=>	null,
                      'tag'			=>	"li",
                      'currentclass'	=>	'active',
                      'modulus'		=>	4
                    ));
                  ?>
                  <?php echo $this->Paginator->next("",
                      array(
                        "escape"	=>	false,
                        'tag'		=>	"li",
                        "class"		=>	"next"
                      ),
                      "<a href='javascript:void(0)'></a>",
                      array(
                        'tag'		=>"li",
                        "escape"	=>	false,
                        "class"		=>	"next"
                      )
                    );
                  ?>
                </ul>
              </div>
              <?php endif;?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php
  }
?>
