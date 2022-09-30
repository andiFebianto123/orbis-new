<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RevisionPastorAnnualDesignerView2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //    DB::statement("DROP VIEW IF EXISTS pastor_annual_designer_views");
    //    DB::statement("CREATE VIEW pastor_annual_designer_views AS
    //         SELECT  
    //             personels.id,
    //             rc_dpw_name, 
    //             short_desc, 
    //             first_name, 
    //             last_name, 
    //             gender,
    //             church_name,
    //             street_address,
    //             city,
    //             province,
    //             postal_code,
    //             country_name,
    //             phone, 
    //             fax,
    //             language,
    //             email, 
    //             second_email, 
    //             marital_status, 
    //             date_of_birth, 
    //             spouse_name,
    //             spouse_date_of_birth,
    //             anniversary,
    //             status,
    //             first_licensed_on,
    //             card,
    //             valid_card_start,
    //             valid_card_end,
    //             current_certificate_number,
    //             notes
    //         FROM 
    //             personels
    //         LEFT JOIN
    //             title_lists ON personels.title_id = title_lists.id
    //         LEFT JOIN
    //             rc_dpwlists ON personels.rc_dpw_id = rc_dpwlists.id
    //         LEFT JOIN
    //             country_lists ON personels.country_id = country_lists.id
    //         LEFT JOIN
    //             (
    //                 SELECT 
    //                     status_histories.personel_id AS personel_id,
    //                     account_status.acc_status AS status,
    //                     temps.id as temps_id
    //                 FROM 
    //                     status_histories 
    //                 LEFT JOIN
    //                 (
    //                     SELECT 
    //                     status_histories.personel_id AS personel_id,
    //                     status_histories.status_histories_id AS status,
    //                     temp2.id
    //                     FROM 
    //                         status_histories
    //                     LEFT JOIN
    //                     (
    //                         SELECT 
    //                         status_histories.id,
    //                         status_histories.personel_id AS personel_id,
    //                         status_histories.status_histories_id AS status
    //                         FROM 
    //                             status_histories
    //                         LEFT JOIN
    //                             status_histories AS temp3 ON temp3.personel_id = status_histories.personel_id
    //                         WHERE
    //                             status_histories.date_status < temp3.date_status
    //                         OR 
    //                         (
    //                             status_histories.date_status = temp3.date_status
    //                             AND
    //                             status_histories.id < temp3.id
    //                         )
    //                     ) AS temp2 ON status_histories.id = temp2.id
    //                 ) AS temps ON status_histories.id = temps.id
    //                 INNER JOIN
	// 					account_status ON account_status.id = status_histories.status_histories_id
    //                 WHERE 
    //                     temps.id IS NULL
    //             ) AS status_personil ON personels.id = status_personil.personel_id;
    //    ");
    // }

    public function up()
    {
       DB::statement("DROP VIEW IF EXISTS pastor_annual_designer_views");
       DB::statement("CREATE VIEW pastor_annual_designer_views AS
            SELECT  
                personels.id,
                rc_dpw_name, 
                short_desc, 
                first_name, 
                last_name, 
                gender,
                church_name,
                street_address,
                city,
                province,
                postal_code,
                country_name,
                phone, 
                fax,
                language,
                email, 
                second_email, 
                marital_status, 
                date_of_birth, 
                spouse_name,
                spouse_date_of_birth,
                anniversary,
                status,
                first_licensed_on,
                card,
                valid_card_start,
                valid_card_end,
                current_certificate_number,
                notes
            FROM 
                personels
            LEFT JOIN
                title_lists ON personels.title_id = title_lists.id
            LEFT JOIN
                rc_dpwlists ON personels.rc_dpw_id = rc_dpwlists.id
            LEFT JOIN
                country_lists ON personels.country_id = country_lists.id
            LEFT JOIN
                (
                    SELECT 
                        status_histories.personel_id AS personel_id,
                        status_histories.status AS status,
                        temps.id as temps_id
                    FROM 
                        status_histories 
                    LEFT JOIN
                    (
                        SELECT 
                        status_histories.personel_id AS personel_id,
                        status_histories.status AS status,
                        temp2.id
                        FROM 
                            status_histories
                        LEFT JOIN
                        (
                            SELECT 
                            status_histories.id,
                            status_histories.personel_id AS personel_id,
                            status_histories.status AS status
                            FROM 
                                status_histories
                            LEFT JOIN
                                status_histories AS temp3 ON temp3.personel_id = status_histories.personel_id
                            WHERE
                                status_histories.date_status < temp3.date_status
                            OR 
                            (
                                status_histories.date_status = temp3.date_status
                                AND
                                status_histories.id < temp3.id
                            )
                        ) AS temp2 ON status_histories.id = temp2.id
                    ) AS temps ON status_histories.id = temps.id
                    WHERE 
                        temps.id IS NULL
                ) AS status_personil ON personels.id = status_personil.personel_id;
       ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {           
        DB::statement("DROP VIEW IF EXISTS pastor_annual_designer_views");
    }
}
