  <table class="table user-list">
                    <thead>
                        <tr>
                            <th><span>S.No</span></th>
                            <th><span>Full Name</span></th>
                           
                            <th><span>Email</span></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $pag=$sn-1; $sn = $sn;
            if(!empty($users)){
                foreach($users as $get){?>
                        <tr>
                            <td><?php echo $sn;?></td>
                            <?php if(!empty($get->profileImage)){ 
                                $url = base_url()."../upload/profile/thumb/".$get->profileImage;
                            }else{ 
                                $url = 'https://bootdey.com/img/Content/avatar/avatar1.png';
                            } ?>
                            <td>
                                <img src="<?php echo $url;?>" alt="">
                                <a class="user-link"><?php echo  ucfirst($get->fullName) ;?></a>
                            </td>
                            
                            <td>
                                <a><?php echo  ucfirst($get->email) ;?></a>
                            </td>
                            <!-- <td style="width: 20%;">
                                <a href="#" class="table-link">
                                    <span class="fa-stack">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                                <a href="#" class="table-link">
                                    <span class="fa-stack">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                                <a href="#" class="table-link danger">
                                    <span class="fa-stack">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </td> -->
                        </tr>
                        <?php $sn++; } } else{ ?>
                <tr class="even pointer">
                    <td class=" " colspan="7">No Record Found</td>
                </tr>
            <?php } ?> 
                    </tbody>
                </table>

   <div class="">   
    <?php echo $links; ?> 
</div>
<!-- /.box-body -->