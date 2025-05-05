<div class="contents animate hide" id="update_school_profile_page">
    <div class="titled">
        <h2>School Profile</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Update School Profile</p>
        </div>
        <div class="middle1">
            <div class="conts" style="">
                <div class="school_logo">
                    <img src="images/board.jpg" id="sch_logos2" alt="">
                    <span class="roundedbtn" id="change_sch_dp"> <i class='fa fa-pen'></i></span>
                </div>
                <div class="conts" style="text-align:center;border-bottom:1px dashed black;">
                    <h3><?php echo $_SESSION['schoolname'];?></h3>
                    <p> <b><u> Update School Information</u></b></p>
                </div>
                <p id="store_sch_information" class="hide"></p>
                <div class="container">
                    <div class="titles">
                        <p>School Basic Information</p>
                    </div>
                    <div class="row">
                        <div class="conts col-md-4">
                            <label for="school_name_s">School name: <span style="color:red;">*</span> <br></label>
                            <input  class="form-control border border-primary rounded" name="school_name_s" id="school_name_s" placeholder ="School Name">
                        </div>
                        <div class="conts col-md-4">
                            <label for="school_motto_s">School Motto: <span style="color:red;">*</span>  <br></label>
                            <input  class="form-control border border-primary rounded" name="school_motto_s" id="school_motto_s" placeholder ="School Motto">
                        </div>
                        <div class="conts col-md-4">
                            <label for="school_vission">School Vision: <span style="color:red;">*</span>  <br></label>
                            <input  class="form-control border border-primary rounded" name="school_vission" id="school_vission" placeholder ="School Vision">
                        </div>
                    </div>
                    <div class="row">
                        <div class="conts col-md-4">
                            <label for="school_codes">School KNEC Code: <span style="color:red;">*</span><span style='color:brown;font-size:12px;'> (Readonly)</span>  <br></label>
                            <input  class="form-control border border-primary rounded" name="school_codes" id="school_codes" readonly placeholder ="School KNEC Code">
                        </div>
                        <div class="conts col-md-4">
                            <label for="school_message_name">School Message Name: <span style="color:red;">*</span>  <br></label>
                            <input  class="form-control border border-primary rounded" name="school_message_name" id="school_message_name" placeholder ="School Message Name">
                        </div>
                        <div class="conts col-md-4">
                            <label for="school_box_no">P.O Box: <span style="color:red;">*</span>  <br></label>
                            <input  class="form-control border border-primary rounded"  name="school_box_no" id="school_box_no" placeholder ="P.O Box">
                        </div>
                    </div>
                    <div class="row">
                        <div class="conts col-md-4">
                            <label for="box_Code">Code: <span style="color:red;">*</span>  <br></label>
                            <input  class="form-control border border-primary rounded"  name="box_Code" id="box_Code" placeholder ="Box Code">
                        </div>
                        <div class="conts col-md-4">
                            <label class="form-control-label" for="sch_physical_address">School Physical Address: <span style="color:red;">*</span>  <br></label>
                            <input  class="form-control border border-primary rounded" id="sch_physical_address" placeholder="School Physical Address">
                        </div>
                        <div class="conts col-md-4">
                            <label for="sch_county">School County: <span style="color:red;">*</span>  <br></label>
                            <select class="form-control border border-primary rounded" name="sch_county" id="sch_county">
                                <option value="" hidden>Select County</option>
                                <option id="Mombasa" value="Mombasa">Mombasa</option>
                                <option id="Kwale" value="Kwale">Kwale</option>
                                <option id="Kilifi" value="Kilifi">Kilifi</option>
                                <option id="Tana River" value="Tana River">Tana River</option>
                                <option id="Lamu" value="Lamu">Lamu</option>
                                <option id="Taita/aveta" value="Taita/Taveta">Taita/Taveta</option>
                                <option id="Garissa" value="Garissa">Garissa</option>
                                <option id="Wajir" value="Wajir">Wajir</option>
                                <option id="Mandera" value="Mandera">Mandera</option>
                                <option id="Marsabit" value="Marsabit">Marsabit</option>
                                <option id="Isiolo" value="Isiolo">Isiolo</option>
                                <option id="Meru" value="Meru">Meru</option>
                                <option id="Tharaka-Nithi" value="Tharaka-Nithi">Tharaka-Nithi</option>
                                <option id="Embu" value="Embu">Embu</option>
                                <option id="Kitui" value="Kitui">Kitui</option>
                                <option id="Machakos" value="Machakos">Machakos</option>
                                <option id="Makueni" value="Makueni">Makueni</option>
                                <option id="Nyandarua" value="Nyandarua">Nyandarua</option>
                                <option id="Nyeri" value="Nyeri">Nyeri</option>
                                <option id="Kirinyaga" value="Kirinyaga">Kirinyaga</option>
                                <option id="Murang'a" value="Murang'a">Murang'a</option>
                                <option id="Kiambu" value="Kiambu">Kiambu</option>
                                <option id="Turkana" value="Turkana">Turkana</option>
                                <option id="West Pokot" value="West Pokot">West Pokot</option>
                                <option id="Samburu" value="Samburu">Samburu</option>
                                <option id="Trans Nzoia" value="Trans Nzoia">Trans Nzoia</option>
                                <option id="Uasin Gishu" value="Uasin Gishu">Uasin Gishu</option>
                                <option id="Elgeyo/Marakwet" value="Elgeyo/Marakwet">Elgeyo/Marakwet</option>
                                <option id="Nandi" value="Nandi">Nandi</option>
                                <option id="Baringo" value="Baringo">Baringo</option>
                                <option id="Laikipia" value="Laikipia">Laikipia</option>
                                <option id="Nakuru" value="Nakuru">Nakuru</option>
                                <option id="Narok" value="Narok">Narok</option>
                                <option id="Kajiado" value="Kajiado">Kajiado</option>
                                <option id="Kericho" value="Kericho">Kericho</option>
                                <option id="Bomet" value="Bomet">Bomet</option>
                                <option id="Kakamega" value="Kakamega">Kakamega</option>
                                <option id="Vihiga" value="Vihiga">Vihiga</option>
                                <option id="Bungoma" value="Bungoma">Bungoma</option>
                                <option id="Busia" value="Busia">Busia</option>
                                <option id="Siaya" value="Siaya">Siaya</option>
                                <option id="Kisumu" value="Kisumu">Kisumu</option>
                                <option id="Homa Bay" value="Homa Bay">Homa Bay</option>
                                <option id="Migori" value="Migori">Migori</option>
                                <option id="Kisii" value="Kisii">Kisii</option>
                                <option id="Nyamira" value="Nyamira">Nyamira</option>
                                <option id="Nairobi" value="Nairobi">Nairobi</option>
                            </select>
                        </div>
                        <div class="conts col-md-4">
                            <label for="sch_country">School Country: <span style="color:red;">*</span>  <br></label>
                            <select class="form-control border border-primary rounded" name="sch_country" id="sch_country">
                                <option value="" hidden>Select Country</option>
                                <option id = "Kenya" value="Kenya">Kenya</option>
                                <option id = "Uganda" value="Uganda">Uganda</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="administrator_contact">
                    <div class="titles">
                        <p>Administrator Information</p>
                    </div>
                    <div class="row">
                        <div class="conts col-md-4">
                            <label for="administrator_name">Administrator Name: <span style="color:red;">*</span>  <br></label>
                            <input   class="form-control border border-primary rounded"  name="administrator_name" id="administrator_name" placeholder = "Admnistrator Name">
                        </div>
                        <div class="conts col-md-4">
                            <label for="administrator_contacts">School Contact: <span style="color:red;">*</span>  <br></label>
                            <input  class="form-control border border-primary rounded"   name="administrator_contacts" id="administrator_contacts" placeholder = "Admnistrator Name">
                        </div>
                        <div class="conts col-md-4">
                            <label for="administrator_email">School Email: <br></label>
                            <input  class="form-control border border-primary rounded"   name="administrator_email" id="administrator_email" placeholder = "Admnistrator Email">
                        </div>
                        <div class="conts col-md-4">
                            <label class="form-control-label" for="school_websites">School Website: <span style="color:red;">*</span>  <br></label>
                            <input type="text" class="form-control" id="school_websites" placeholder="ex. www.ladybirdsmis.com">
                        </div>
                    </div>
                </div>
                <div class="conts">
                    <p id="school_information_err_handler"></p>
                </div>
                <div class="btns">
                    <button type='button' id='update_school_in4'>Update</button>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>