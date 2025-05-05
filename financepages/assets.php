<div class="contents animate hide" id="asset_accounts">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Asset Accounts</p>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="row">
                <div class="notice1 col-md-12">
                    <!-- <button type='button' id='back_to_fees_payment_2'><i class="fa fa-arrow-left"></i> Back to Fees Payment</button> -->
                    <div class="notify">
                        <p><strong>Important:</strong></p>
                    </div>
                    <ul>
                        <li> VIEW, UPDATE AND DELETE THE SCHOOL ASSETS.</li>
                    </ul>
                </div>
            </div>
            <div class="container" id="asset-list">
                <button class="nav-bar-btns" id="register-new-asset"><i class="fa fa-plus"></i> Register New Asset <img class="hide" src="images/ajax_clock_small.gif" id="new-asset-loader"></button>
                <div id="asset-lists-notifier"></div>
                <div class="hide" id="asset-data"></div>
                <div class="table_container">
                    <h6 class="text-center my-2"><u>Asset List</u></h6>
                    <input type="hidden" name="" value="1" id="asset-page">
                    <input type="hidden" name="" value="1" id="maximum-page-asset">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <input type="text" class="text form-control w-100" id="search-assets" placeholder="Enter Keyword Here">
                        </div>
                    </div>
                    <p id="asset-notices-2"></p>
                    <div id="asset-data-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Assets Name</th>
                                    <th>Asset Category</th>
                                    <th>Date Acquired.</th>
                                    <th>Value</th>
                                    <th>rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Toyota KBX 207 A</td>
                                    <td>Motor Vehicle & Machinery</td>
                                    <td>21st Jun 2023 @ 13:04PM.</td>
                                    <td>Kes 3,000,000</td>
                                    <td>56% <i class="fas fa-arrow-up text-success"></i></td>
                                    <td><span style="font-size:12px;" class="link view_students" id="view1"><i class="fa fa-pen-fancy"></i> Edit </span></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Toyota KBX 207 A</td>
                                    <td>Motor Vehicle & Machinery</td>
                                    <td>21st Jun 2023 @ 13:04PM.</td>
                                    <td>Kes 1,000,000</td>
                                    <td>56% <i class="fas fa-arrow-down text-danger"></i></td>
                                    <td><span style="font-size:12px;" class="link view_students" id="view1"><i class="fa fa-pen-fancy"></i> Edit </span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row w-50">
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="previous-asset-page"><i class="fa fa-arrow-left"></i><i class="fa fa-arrow-left"></i></span>
                        </div>
                        <div class="col-sm-4">
                            <span id="asset-current-page" class="text-center">Page 1 of 2</span>
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="next-asset-page"><i class="fa fa-arrow-right"></i><i class="fa fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container hide" id="register-asset">
                <button id="back-to-assets"><i class="fa fa-arrow-left"></i> Back to Assets </button>
                <div class="row my-2">
                    <div class="col-md-12 my-2">
                        <h6 class="text-center"><u>Register Asset</u></h6>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-name" class="form-label"><b>Asset Name</b></label>
                        <input type="text" name="asset-name" id="asset-name" class="form-control w-100" placeholder="Asset Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-category" class="form-label"><b>Asset Category</b></label>
                        <select name="asset-category" id="asset-category" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="1">Land</option>
                            <option value="2">Buildings</option>
                            <option value="3">Motor Vehicle</option>
                            <option value="4">Furniture & Fittings</option>
                            <option value="5">Computer & ICT Equipments</option>
                            <option value="6">Plant & Equipments</option>
                            <option value="7">Capital Work in Progress</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-acquiry-date" class="form-label"><b>Date Of Acquiry (Year is the most important)</b></label>
                        <input type="date" name="asset-acquiry-date" id="asset-acquiry-date" class="form-control w-100" value="<?=date("Y-m-d")?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-original-value" class="form-label"><b>Asset Original Value</b></label>
                        <input type="number" name="asset-original-value" id="asset-original-value" class="form-control w-100" placeholder="Original Value">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="value-acquisition-option" class="form-label"><b>Value Acquisition Option</b></label>
                        <select name="value-acquisition-option" id="value-acquisition-option" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="1">Straightline Method (decrease)</option>
                            <option value="2">Reducing Balance Method (decrease)</option>
                            <option value="3">Straightline Method (increase)</option>
                            <option value="4">Reducing Balance Method (increase)</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="value-acquisition-percentage" class="form-label"><b>Value Acquisition Percentage</b></label>
                        <input type="number" name="value-acquisition-percentage" id="value-acquisition-percentage" class="form-control w-100" placeholder="Ex : 5% (type without %)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-description" class="form-label"><b>Description </b><small>(<?= date("D dS M Y") ?>)</small></label>
                        <textarea name="asset-description" id="asset-description" cols="30" rows="5" class="form-control w-100" placeholder="Write a short narative about the supplier!"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="asset-error"></div>
                        <button id="save-assets-btn"><i class="fa fa-save"></i> Save Asset <img class="hide" src="images/ajax_clock_small.gif" id="save-assets-loader"></button>
                    </div>
                </div>
            </div>
            <div class="container p-1 mx-auto hide" id="edit-assets">
                <button id="back-to-assets-edit"><i class="fa fa-arrow-left"></i> Back to Assets </button>
                <div class="row my-2">
                    <div class="col-md-12 my-2">
                        <h6 class="text-center"><u>Edit Asset</u> <img class="hide" src="images/ajax_clock_small.gif" id="asset_data_loader"></h6>
                        <p class="hide" id="asset_data_holder"></p>
                    </div>
                    <form class="col-md-12" target="_blank" method="POST" action="reports/reports.php">
                        <div class="border border-secondary rounded mx-auto w-75 py-2 px-2 my-2">
                            <h6 class="text-secondary text-center"><u>Print Statement of Asset Accounts</u></h6>
                            <input type="hidden" name="print_statement_of_account" value="true">
                            <input type="hidden" name="asset_id" id="asset-id">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="btn btn-outline-danger btn-sm" id="dispose_assets_btn"><i class="fas fa-trash"></i> Dispose Asset</p>
                                    <div class="hide" id="recycles">
                                        <p class="btn btn-outline-success btn-sm" id="recover_assets_btn"><i class="fas fa-recycle"></i> Recover Asset</p>
                                        <p class="text-success" ><b>Asset disposed on :</b> <span id="date_asset_disposed">N/A</span></p>
                                        <p class="text-success" ><b>Asset dispose value :</b> <span id="asset_dispose_value">N/A</span></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" class="btn btn-sm btn-primary text-white" value="Print Statement of Accounts">
                                </div>
                            </div>
                            <div class="message_contents mt-3 hide" id="dispose_asset_window">
                                <label class="form-control-label"><u>Confirm : </u></label>
                                <p>- Disposing the asset means that its value will be written of this financial year?</p>
                                <p>- The action is reversible.</p>
                                <hr class="my-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="set_disposed_date" class="form-control-label"><b>Date Category</b></label>
                                        <input type="date" class="form-control" id="set_disposed_date" value="<?=date("Y-m-d")?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dispose_value" class="form-control-label"><b>Asset Value</b></label>
                                        <input type="number" class="form-control" id="dispose_value" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="dispose_asset"><i class="fas fa-trash"></i>  Dispose <img class="hide" src="images/ajax_clock_small.gif" id="dispose_asset_loader"></button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="cancel_asset_disposal"><i class="fas fa-x"></i> Cancel</button>
                                    </div>
                                    <p id="asset-dispose-error"></p>
                                </div>
                            </div>
                            <div class="message_contents mt-3 hide" id="recover_asset_confirm">
                                <label class="form-control-label"><u>Confirm : </u></label>
                                <p>- Recovering an asset means you have repossesed it!</p>
                                <p>- Normal depreciation will continue as ussual.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" id="recover_asset_confirm_btn"><i class="fas fa-recycle"></i>  Recover Asset<img class="hide" src="images/ajax_clock_small.gif" id="recover_asset_loader"></button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="cancel_asset_recovery"><i class="fas fa-x"></i> Cancel</button>
                                    </div>
                                    <p id="asset-recovery-error"></p>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-4 form-group">
                        <label for="asset-name-edit" class="form-label"><b>Asset Name</b></label>
                        <input type="text" name="asset-name-edit" id="asset-name-edit" class="form-control w-100" placeholder="Asset Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-category-edit" class="form-label"><b>Asset Category</b></label>
                        <select name="asset-category-edit" id="asset-category-edit" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="1">Land</option>
                            <option value="2">Buildings</option>
                            <option value="3">Motor Vehicle</option>
                            <option value="4">Furniture & Fittings</option>
                            <option value="5">Computer & ICT Equipments</option>
                            <option value="6">Plant & Equipments</option>
                            <option value="7">Capital Work in Progress</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-acquiry-date-edit" class="form-label"><b>Date Of Acquiry (Year is the most important)</b></label>
                        <input type="date" name="asset-acquiry-date-edit" id="asset-acquiry-date-edit" class="form-control w-100" value="<?=date("Y-m-d")?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-original-value-edit" class="form-label"><b>Asset Original Value</b></label>
                        <input type="number" name="asset-original-value-edit" id="asset-original-value-edit" class="form-control w-100" placeholder="Original Value">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="value-acquisition-option-edit" class="form-label"><b>Value Acquisition Option</b></label>
                        <select name="value-acquisition-option-edit" id="value-acquisition-option-edit" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="1">Straightline Method</option>
                            <option value="2">Reducing Balance Method</option>
                            <!-- <option value="3">Straightline Method (increase)</option>
                            <option value="4">Reducing Balance Method (increase)</option> -->
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="value-acquisition-percentage-edit" class="form-label"><b>Value Acquisition Percentage</b></label>
                        <input type="number" name="value-acquisition-percentage-edit" id="value-acquisition-percentage-edit" class="form-control w-100" placeholder="Ex : 5% (type without %)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="asset-description-edit" class="form-label"><b>Description </b><small>(<?= date("D dS M Y") ?>)</small></label>
                        <textarea name="asset-description-edit" id="asset-description-edit" cols="30" rows="5" class="form-control w-100" placeholder="Write a short narative about the supplier!"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="asset-error-edit"></div>
                        <button id="update-assets-btn"><i class="fa fa-save"></i> Update Asset <img class="hide" src="images/ajax_clock_small.gif" id="save-assets-loader-edit"></button>
                    </div>
                </div>
                <hr class="my-2 py-1">
                <div class="row my-2">
                    <div class="col-md-12">
                        <h6 class="text-center">Asset Depreciation Account <img class="hide" src="images/ajax_clock_small.gif" id="asset-account-loaders"></h6>
                        <div class="hide" id="asset-accounts-holder"></div>
                    </div>
                    <div class="col-md-6 mx-auto" id="asset_transaction_table">
                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>Asset Name</th>
                                <th>Debit (Dr)</th>
                                <th>Credit (Cr)</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Land</td>
                                <td class="bg-primary">Kes 10,000</td>
                                <td class="bg-secondary">-</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Depreciation</td>
                                <td class="bg-primary">-</td>
                                <td class="bg-secondary">- Kes 500</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>