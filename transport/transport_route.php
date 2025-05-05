<div class="contents animate hide" id="transport_n_route">
    <div class="titled">
        <h2>Transport System</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Routes Prices and School vans</p>
                </div>
                <div class="col-md-3">
                    <span id="transport_system_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- Add, update and delete routes at this window.</p>
                <p>- Add, update and delete the school vans owned by the schools.</p>
                <p>- Add information like the vans insurance expiration date and the next car service date and you will be notified when due.</p>
            </div>
            <div class="conts">
                <div class="staff_information" id="route_information">
                    <div class="enroll_staf " id="route_list">
                        <div class="conts">
                            <h6 style="text-align:center;"><b>Transport Routes</b> <img class="hide" src="images/ajax_clock_small.gif" id="routes_loader"></h6>
                        </div>
                        <div class="conts">
                            <p>- Below are a list of routes registered.</p>
                        </div>
                        <div class="conts">
                        <p class="block_btn" id="register_route_btn"><i class="fas fa-plus"></i> Register New Route</p>
                        </div>
                        <div class="conts">
                            <div class="table_holders">
                                <p class="hide" id="myrouteinformation"></p>
                            </div>
                            <div class="row m-0">
                                <div class="col-sm-7">
                                </div>
                                <div class="col sm-5">
                                    <div class="input-group my-3">
                                        <input type="text" name="searchkey" id="searchkey2" class="form-control border border-dark rounded p-2 text-xs font-weight-bold" style="width:fit-content;" placeholder="Enter keyword to search table...">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" id="transDataReciever2">
                                <table class="table">
                                    <tr>
                                        <th>No.</th>
                                        <th>Route Name</th>
                                        <th>Route Price.</th>
                                        <th>Route Arears</th>
                                        <th>Actions</th>
                                    </tr>
                                    <tr>
                                        <td>1. </td>
                                        <td>ISUZU</td>
                                        <td>Section 2</td>
                                        <td>Peter Karani</td>
                                        <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                    </tr>
                                    <tr>
                                        <td>2. </td>
                                        <td>ISUZU</td>
                                        <td>Section 2</td>
                                        <td>Peter Karani</td>
                                        <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                    </tr>
                                    <tr>
                                        <td>3. </td>
                                        <td>ISUZU</td>
                                        <td>Section 2</td>
                                        <td>Peter Karani</td>
                                        <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row mt-5" id="tablefooter2">
                                <div class="col-sm-12 col-md-5">
                                    <div class="container-fluid">
                                        <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo2">1 </span> to <span class="text-primary" id="finishNo2">10</span> of <span id="tot_records2"></span> Records.</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate2">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item first" id="datatable_first2"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav2">First</a></li>
                                            <li class="paginate_button page-item previous mx-1" id="datatable_previous2"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac2">Prev</a></li>
                                            <li class="paginate_button page-item previous active mx-3" id="datatable_previous2"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav2">1</a></li>
                                            <li class="paginate_button page-item next mx-1" id="datatable_next2"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav2">Next</a></li>
                                            <li class="paginate_button page-item last mx-1" id="datatable_last2"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav2">Last</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="enroll_staf hide" id="register_route">
                        <h6 class="text-center"><b>Register Route</b></h6>
                        <p class="block_btn" id="back_to_routes"><i class="fas fa-arrow-left"></i> Back to route list</p>
                        <div class="cont">
                            <p>- Fill all the fields to register a new route</p>
                            <p>- For multiple route areas seperate with commas</p>
                        </div>
                        <div class="row">
                            <div class="col-md-4 my-1">
                                <label for="route_name" class="form-control-label">Route Name:</label>
                                <input type="text" style="width: 100% !important;" class="form-control" id="route_name" placeholder="Route name/ Alias">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="route_price" class="form-control-label">Route Price:</label>
                                <input type="number" style="width: 100% !important;" class="form-control" id="route_price" placeholder="eg 1000">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="route_area_coverage" class="form-control-label">Route areas: <strong><small>seperate with a comma</small></strong></label>
                                <textarea class="form-control" name="route_area_coverage" id="route_area_coverage" cols="10" rows="1" placeholder=""></textarea>
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col-md-6">
                                <p id="route_err_handler"></p>
                                <p class="block_btn rounded" style="width: fit-content;" id="save_new_route"><i class="fas fa-save"></i> Save Route <img class="hide" src="images/ajax_clock_small.gif" id="route_loader"></p>
                            </div>
                            <div class="col-md-6">
                                <p class="block_btn rounded" style="width: fit-content;" id="cancel_save_route"><i class="fas fa-save"></i> Cancel</p>
                            </div>
                        </div>
                    </div>
                    <div class="enroll_staf hide" id="view_route_infor">
                        <h6 class="text-center"><b>View route information</b> <img class="hide" src="images/ajax_clock_small.gif" id="view_route_loader"></h6>
                        <p class="hide" id="routes_in4"></p>
                        <p class="block_btn" id="back_to_routes2"><i class="fas fa-arrow-left"></i> Back to route list</p>
                        <div class="conts">
                            <p>- Change information about the route in the fields below</p>
                        </div>
                        <div class="cont">
                            <img class="hide" src="images/ajax_clock_small.gif" id="route_err_routed">
                            <p id="delete_err_route"></p>
                            <p class="link" id="delete_route" style="width: fit-content;"><i class="fas fa-trash"></i> Delete Route Permanently</p>
                        </div>
                        <div class="row my-2">
                            <div class="col-md-4 my-2">
                                <input type="hidden" id="route_id">
                                <label for="routes_names" class="form-control-label" id="">Route Name {<span id="routes_names2"></span>}</label>
                                <input type="text" style="width: 100%;" class="form-control" id="routes_names" placeholder="Route Name">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="routes_prices" class="form-control-label" id="">Route Prices {<span id="routes_prices2"></span>}</label>
                                <input type="text" style="width: 100%;" class="form-control" id="routes_prices" placeholder="Route Prices">
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="routes_areas" class="form-control-label" id="">Route Areas</label>
                                <textarea class="form-control border border-dark" id="routes_areas" cols="30" rows="5"></textarea>
                            </div>
                            <!-- <div class="col-md-4 my-2">
                                <label for="routes_names" class="form-control-label" id="routes_names">Route Vans</label>
                                <div class="card border border-dark">
                                    <div class="card-header">
                                        <h6 class="card-text text-center">Route Vans</h6>
                                    </div>
                                    <div class="card-body">
                                        <p> - Hillary Van 1 {ins: June 12th 2022}</p>
                                    </div>
                            </div> -->
                        </div>
                        <row class="my-2">
                            <div class="cont">
                                <p id="update_route_err_handler"></p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="block_btn" id="update_route"><i class="fas fa-upload"></i> Update Route <img class="hide" src="images/ajax_clock_small.gif" id="updates_routes_loader"> <span class="hide" id="updates_routes_loader"><i class="fas fa-spinner fa-spin"></i></span></p>
                                </div>
                            </div>
                        </row>
                    </div>
                </div>
                <hr>
                <div class="staff_information" id="vans_information">
                    <!-- view vans available in school -->
                    <div class="enroll_staf" id="viewRegisteredCars">
                        <div class="conts">
                            <h6 style="text-align:center;"><b>School Vans</b> <img class="hide" src="images/ajax_clock_small.gif" id="van_loader"></h6>
                        </div>
                        <div class="conts">
                            <p>- Below are a list of vans registered.</p>
                        </div>
                        <div class="conts">
                        <p class="block_btn" id="add_school_vans"><i class="fas fa-plus"></i> Register School Van</p>
                        </div>
                        <div class="conts">
                            <div class="table_holders">
                                <p class="hide" id="vans_informations"></p>
                            </div>
                            <div class="row m-0">
                                <div class="col-sm-7">
                                </div>
                                <div class="col sm-5">
                                    <div class="input-group my-3">
                                        <input type="text" name="searchkey" id="searchkey1" class="form-control border border-dark rounded p-2 text-xs font-weight-bold" style="width:fit-content;" placeholder="Enter keyword to search table...">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" id="transDataReciever1">
                                <table class="table">
                                    <tr>
                                        <th>No.</th>
                                        <th>Van Name.</th>
                                        <th>Manufacturer</th>
                                        <th>Driver</th>
                                        <th>Licence Expiration</th>
                                        <th>Actions</th>
                                    </tr>
                                    <tr>
                                        <td>1. </td>
                                        <td>PRODIGAL SON 2</td>
                                        <td>ISUZU</td>
                                        <td>Peter Karani</td>
                                        <td>30th March 2022</td>
                                        <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                    </tr>
                                    <tr>
                                        <td>2. </td>
                                        <td>PRODIGAL SON 2</td>
                                        <td>ISUZU</td>
                                        <td>Peter Karani</td>
                                        <td>30th March 2022</td>
                                        <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                    </tr>
                                    <tr>
                                        <td>3. </td>
                                        <td>PRODIGAL SON 2</td>
                                        <td>ISUZU</td>
                                        <td>Peter Karani</td>
                                        <td>30th March 2022</td>
                                        <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row mt-5" id="tablefooter1">
                                <div class="col-sm-12 col-md-5">
                                    <div class="container-fluid">
                                        <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo1">1 </span> to <span class="text-primary" id="finishNo1">10</span> of <span id="tot_records1"></span> Records.</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate1">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item first" id="datatable_first1"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav1">First</a></li>
                                            <li class="paginate_button page-item previous mx-1" id="datatable_previous1"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac1">Prev</a></li>
                                            <li class="paginate_button page-item previous active mx-3" id="datatable_previous1"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav1">1</a></li>
                                            <li class="paginate_button page-item next mx-1" id="datatable_next1"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav1">Next</a></li>
                                            <li class="paginate_button page-item last mx-1" id="datatable_last1"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav1">Last</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- get the school van data -->
                    <div class="enroll_staff hide" id="save_van_window">
                        <h6 class="text-center"><u><b>Register School Van</b></u></h6>
                        <p class="block_btn" id="back_to_vans"><i class="fas fa-arrow-left"></i> Back to van list</p>
                        <div class="cont">
                            <p>- Fill all the fields to register the school van</p>
                            <!-- <p>- You will be notified about the van insurance due date and the vans next service date</p> -->
                        </div>
                        <div class="row my-2">
                            <div class="col-md-4">
                                <label for="bus_name" class="form-control-label">Van name / Alias:</label>
                                <input style="width: 100% !important;" type="text" class="form-control" id="bus_name" placeholder="Bus name/ Alias">
                            </div>
                            <div class="col-md-4">
                                <label for="van_regno" class="form-control-label">Van Reg No :</label>
                                <input style="width: 100% !important;" type="text" class="form-control" id="van_regno" placeholder="Licence plate no">
                            </div>
                            <div class="col-md-4">
                                <label for="van_model" class="form-control-label">Van Make/ Model:</label>
                                <input style="width: 100% !important;" type="text" class="form-control" id="van_model" placeholder="Make / Model">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="van_seater_size" class="form-control-label">Van Seater size:</label>
                                <input style="width: 100% !important;" type="number" class="form-control" id="van_seater_size" placeholder="Carrying capacity">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="insurance_date" class="form-control-label">Van insurance expiration date:</label>
                                <input style="width: 100% !important;" type="date" class="form-control" id="insurance_date" placeholder="Insurance expiration date">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="service_date" class="form-control-label">Van next service date:</label>
                                <input style="width: 100% !important;" type="date" class="form-control" id="service_date" placeholder="Next service date">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="van_driver" class="form-control-label">Van`s Driver:</label>
                                <img src="images/ajax_clock_small.gif" id="vans_driver_load">
                                <span id="driver_lists">
                                </span>
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="van_routes" class="form-control-label">Van`s Route:</label>
                                <img src="images/ajax_clock_small.gif" id="vans_routes">
                                <span id="routes_lists">
                                </span>
                            </div>
                        </div>
                        <div class="cont">
                            <p id="save_van_err"></p>
                        </div>
                        <div class="row my-1">
                            <div class="col-md-6">
                                <p class="block_btn rounded" style="width: fit-content;" id="save_new_van"><i class="fas fa-save"></i> Save Van <span class="hide" id="save_bus_loader"><i class="fas fa-spinner fa-spin"></i></span></p>
                            </div>
                        </div>
                    </div>
                    <!-- get the school van data -->
                    <div class="enroll_staff hide" id="update_van_window">
                        <h6 class="text-center"><u><b>Update / View School Van</b></u> <img class="hide" src="images/ajax_clock_small.gif" id="van_loader1"> </h6>
                        <p class="block_btn" id="back_to_vans1"><i class="fas fa-arrow-left"></i> Back to van list</p>
                        <p class="hide" id="update_data"></p>
                        <p>By clicking the button below you are confirming deleting of the school van <img class="hide" src="images/ajax_clock_small.gif" id="van_delete_it"></p>
                        <p id="delete_error_hand"></p>
                        <p class="link" style="width: fit-content;" id="delete_van"><i class="fas fa-trash"></i> Permanently delete school van</p>
                        <div class="cont">
                            <p>- Fill all the fields to register the school van</p>
                            <!-- <p>- You will be notified about the van insurance due date and the vans next service date</p> -->
                        </div>
                        <div class="row my-2">
                            <div class="col-md-4">
                                <input type="hidden" name="van_id_in" id="van_id_in">
                                <label for="bus_name1" class="form-control-label">Van name / Alias {<span id="vans_names"></span>} :</label>
                                <input style="width: 100% !important;" type="text" class="form-control" id="bus_name1" placeholder="Bus name/ Alias">
                            </div>
                            <div class="col-md-4">
                                <label for="van_regno1" class="form-control-label">Van Reg No {<span id="vans_regnos"></span>}:</label>
                                <input style="width: 100% !important;" type="text" class="form-control" id="van_regno1" placeholder="Licence plate no">
                            </div>
                            <div class="col-md-4">
                                <label for="van_model1" class="form-control-label">Van Make/ Model {<span id="vans_models"></span>}:</label>
                                <input style="width: 100% !important;" type="text" class="form-control" id="van_model1" placeholder="Make / Model">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="van_seater_size1" class="form-control-label">Van Seater size {<span id="vans_seater_sizes"></span>} :</label>
                                <input style="width: 100% !important;" type="number" class="form-control" id="van_seater_size1" placeholder="Carrying capacity">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="insurance_date1" class="form-control-label">Van insurance expiration date {<span id="vans_exp_dates"></span>} : </label>
                                <input style="width: 100% !important;" type="date" class="form-control" id="insurance_date1" placeholder="Insurance expiration date">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="service_date1" class="form-control-label">Van next service date {<span id="vans_next_exp_dates"></span>} :</label>
                                <input style="width: 100% !important;" type="date" class="form-control" id="service_date1" min="<?php echo date('Y/m/d');?>" placeholder="Next service date">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="van_driver1" class="form-control-label">Van`s Driver {<span id="vans_drivers"></span>} : </label>
                                <img src="images/ajax_clock_small.gif" id="vans_driver_load1">
                                <span id="driver_lists1">
                                </span>
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="van_routes1" class="form-control-label">Van`s Route {<span id="vans_routes12"></span>} :</label>
                                <img src="images/ajax_clock_small.gif" id="vans_routes1">
                                <span id="routes_lists1">
                                </span>
                            </div>
                        </div>
                        <div class="cont">
                            <p id="update_van_err"></p>
                        </div>
                        <div class="row my-1">
                            <div class="col-md-6">
                                <p class="block_btn rounded" style="width: fit-content;" id="update_new_van"><i class="fas fa-upload"></i> Update Van <img class="hide" src="images/ajax_clock_small.gif" id="update_bus_loader"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>