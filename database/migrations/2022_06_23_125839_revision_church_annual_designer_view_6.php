<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RevisionChurchAnnualDesignerView5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS church_annual_designer_views");
        DB::statement("CREATE VIEW church_annual_designer_views AS
            SELECT 
                churches.id, 
                rc_dpw_name, 
                church_name, 
                lead_pastor_name, 
                entities_type, 
                contact_person, 
                church_address, 
                office_address, 
                city, 
                province, 
                postal_code, 
                country_name, 
                phone, 
                fax, 
                first_email, 
                founded_on, 
                service_time_church, 
                notes, 
                status 
                FROM 
                churches 
                LEFT JOIN church_types ON churches.church_type_id = church_types.id 
                LEFT JOIN rc_dpwlists ON churches.rc_dpw_id = rc_dpwlists.id 
                LEFT JOIN country_lists ON churches.country_id = country_lists.id 
                LEFT JOIN (
                    SELECT 
                    status_history_churches.churches_id AS churches_id, 
                    status_history_churches.status AS status, 
                    temps.id as temps_id 
                    FROM 
                    status_history_churches 
                    LEFT JOIN (
                        SELECT 
                        status_history_churches.churches_id AS churches_id, 
                        status_history_churches.status AS status, 
                        temp2.id 
                        FROM 
                        status_history_churches 
                        LEFT JOIN (
                            SELECT 
                            status_history_churches.id, 
                            status_history_churches.churches_id AS churches_id, 
                            status_history_churches.status AS status 
                            FROM 
                            status_history_churches 
                            LEFT JOIN status_history_churches AS temp3 ON temp3.churches_id = status_history_churches.churches_id 
                            WHERE 
                            status_history_churches.date_status < temp3.date_status 
                            OR (
                                status_history_churches.date_status = temp3.date_status 
                                AND status_history_churches.id < temp3.id
                            )
                        ) AS temp2 ON status_history_churches.id = temp2.id
                    ) AS temps ON status_history_churches.id = temps.id 
                    WHERE 
                    temps.id IS NULL
                ) AS status_churches ON churches.id = status_churches.churches_id 
                LEFT JOIN (
                    SELECT 
                    churches_id, 
                    group_concat(name) as lead_pastor_name 
                    from 
                    (
                        SELECT 
                        churches_id, 
                        concat(name, ' (', ministry_role, ')') AS name 
                        from 
                        structure_churches 
                        INNER JOIN (
                            SELECT 
                            personels.id, 
                            CONCAT(first_name, ' ', last_name) as name 
                            FROM 
                            personels
                        ) as pd on pd.id = structure_churches.personel_id 
                        INNER JOIN (
                            SELECT 
                            id, 
                            ministry_role 
                            FROM 
                            ministry_roles
                        ) as md on md.id = structure_churches.title_structure_id
                    ) as ln 
                    GROUP by 
                    churches_id
                ) as lnd on lnd.churches_id = churches.id
       ");
    }


    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS church_annual_designer_views");
    }
}
