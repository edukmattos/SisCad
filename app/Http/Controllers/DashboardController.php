<?php

namespace SisCad\Http\Controllers;

use Illuminate\Http\Request;

use JasperPHP\JasperPHP as JasperPHP;

use SisCad\Http\Requests;
use SisCad\Http\Controllers\Controller;

use SisCad\Repositories\PlanRepository;
use SisCad\Repositories\RegionRepository;
use SisCad\Repositories\MemberRepository;
use SisCad\Repositories\CityRepository;
use SisCad\Repositories\PartnerRepository;
use SisCad\Repositories\PartnerTypeRepository;

class DashboardController extends Controller
{
    private $regionRepository;
    private $planRepository;
    private $memberRepository;
    private $cityRepository;
    private $partnerRepository;
    private $partnertypeRepository;

    public function __construct(RegionRepository $regionRepository, CityRepository $cityRepository, PlanRepository $planRepository, MemberRepository $memberRepository, PartnerRepository $partnerRepository, PartnerTypeRepository $partner_typeRepository)
    {
        $this->regionRepository         = $regionRepository;
        $this->planRepository           = $planRepository;
        $this->memberRepository         = $memberRepository;
        $this->cityRepository           = $cityRepository;
        $this->partnerRepository        = $partnerRepository;
        $this->partner_typeRepository   = $partner_typeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pc_members()
    {
        $plan1 = $this->planRepository->findPlanById(1);
        
        $plan1_allmembersbystatus = $this->memberRepository->allMembersByPlanStatus(1, 2);

        #$plan1_allmembersbystatus = $this->memberRepository->allMembersByPlanStatus(1, 2);

        $plan1_allmembersmalebystatus = $this->memberRepository->allMembersGenderByPlanStatus(1, 2, 1);

        $plan1_allmembersfemalebystatus = $this->memberRepository->allMembersGenderByPlanStatus(1, 2, 2);

        $plan1_allmembersemailbystatus = $this->memberRepository->allMembersEmailByPlanStatus(1, 2);

        $plan1_regions = $this->regionRepository->allRegions();
        $plan1_regions->load(['members' => function($q) 
        {
            $q->whereMemberStatusId(2);
            $q->wherePlanId(1);
            $q->get();
        }]);

        $plan1_cities = $this->cityRepository->allCities();
        $plan1_cities->load(['members' => function($q) 
        {
            $q->whereMemberStatusId(2);
            $q->wherePlanId(1);
            $q->get();
        }]);


        
        $plan2 = $this->planRepository->findPlanById(2);
        
        $plan2_allmembersbystatus = $this->memberRepository->allMembersByPlanStatus(2, 2);

        #$plan2_allmembersbystatus = $this->memberRepository->allMembersByPlanStatus(2, 2);

        $plan2_allmembersmalebystatus = $this->memberRepository->allMembersGenderByPlanStatus(2, 2, 1);

        $plan2_allmembersfemalebystatus = $this->memberRepository->allMembersGenderByPlanStatus(2, 2, 2);

        $plan2_allmembersemailbystatus = $this->memberRepository->allMembersEmailByPlanStatus(2, 2);
        
        $plan2_regions = $this->regionRepository->allRegions();
        $plan2_regions->load(['members' => function($q) 
        {
            $q->whereMemberStatusId(2);
            $q->wherePlanId(2);
            $q->get();
        }]);

        $plan2_cities = $this->cityRepository->allCities();
        $plan2_cities->load(['members' => function($q) 
        {
            $q->whereMemberStatusId(2);
            $q->wherePlanId(2);
            $q->get();
        }]);
        
        $plan_regions = $this->regionRepository->allRegions();
        $plan_regions->load(['members' => function($q) 
        {
            $q->whereMemberStatusId(2);
            $q->get();
        }]);

        $plan_cities = $this->cityRepository->allCities();
        $plan_cities->load(['members' => function($q) 
        {
            $q->whereMemberStatusId(2);
            $q->get();
        }]);

        return view('dashboard.members', compact('plan1', 'plan1_allmembersbystatus', 'plan1_allmembersmalebystatus', 'plan1_allmembersfemalebystatus', 'plan1_allmembersemailbystatus', 'plan1_regions', 'plan1_cities', 'plan2', 'plan2_allmembersbystatus', 'plan2_allmembersmalebystatus', 'plan2_allmembersfemalebystatus', 'plan2_allmembersemailbystatus', 'plan2_regions', 'plan2_cities', 'plan_regions', 'plan_cities'));//
    }

    public function members_reports(Request $request)
    {
        $rpt_model                       = $request->get('model');
        
        $srch_plan_id                    = $request->get('plan_id');
        $srch_region_id                  = $request->get('region_id');
        $srch_city_id                    = $request->get('city_id');
        $srch_member_status_id           = $request->get('status_id');
        
        $database = \Config::get('database.connections.mysql');

        if($rpt_model=='allMembersByPlanRegionStatus')
        {
            $output = public_path() . '/reports/members/allMembersByPlanRegionStatus_'.date("Ymd_His");  
            $input = public_path() . '/reports/members/allMembersByPlanRegionStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_region_id" => $srch_region_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByPlanCityStatus')
        {
            $output = public_path() . '/reports/members/allMembersByPlanCityStatus_'.date("Ymd_His");  
            $input = public_path() . '/reports/members/allMembersByPlanCityStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_city_id" => $srch_city_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByPlanStatus')
        {
            $output = public_path() . '/reports/members/allMembersByPlanStatus_'.date("Ymd_His");  
            $input = public_path() . '/reports/members/allMembersByPlanStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByRegionStatus')
        {
            $output = public_path() . '/reports/members/allMembersByRegionStatus_'.date("Ymd_His");  
            $input = public_path() . '/reports/members/allMembersByRegionStatus.jrxml'; 

            $conditions = array("jsp_region_id" => $srch_region_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByCityStatus')
        {
            $output = public_path() . '/reports/members/allMembersByCityStatus_'.date("Ymd_His");  
            $input = public_path() . '/reports/members/allMembersByCityStatus.jrxml'; 

            $conditions = array("jsp_city_id" => $srch_city_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByStatus')
        {
            $output = public_path() . '/reports/members/allMembersByStatus_'.date("Ymd_His");  
            $input = public_path() . '/reports/members/allMembersByStatus.jrxml'; 

            $conditions = array('jsp_member_status_id' => $srch_member_status_id);
        }

        #dd($conditions);

        $ext = "pdf";
       
        $report = new JasperPHP;
        $report->process
        (
            $input, 
            $output, 
            array('pdf'),
            $conditions,
            $database  
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=members_reports_'.date("Ymd_His").'.'.$ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($output.'.'.$ext));
        flush();
        readfile($output.'.'.$ext);
        unlink($output.'.'.$ext);
    }

    public function members_labels(Request $request)
    {
        $rpt_model                       = $request->get('model');
        
        $srch_plan_id                    = $request->get('plan_id');
        $srch_region_id                  = $request->get('region_id');
        $srch_city_id                    = $request->get('city_id');
        $srch_member_status_id           = $request->get('status_id');
        
        $database = \Config::get('database.connections.mysql');
                        
        if($rpt_model=='allMembersByPlanRegionStatus')
        {
            $output = public_path() . '/labels/members/allMembersByPlanRegionStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByPlanRegionStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_region_id" => $srch_region_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByPlanCityStatus')
        {
            $output = public_path() . '/labels/members/allMembersByPlanCityStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByPlanCityStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_city_id" => $srch_city_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByPlanStatus')
        {
            $output = public_path() . '/labels/members/allMembersByPlanStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByPlanStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByRegionStatus')
        {
            $output = public_path() . '/labels/members/allMembersByRegionStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByRegionStatus.jrxml'; 

            $conditions = array("jsp_region_id" => $srch_region_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByCityStatus')
        {
            $output = public_path() . '/labels/members/allMembersByCityStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByCityStatus.jrxml'; 

            $conditions = array("jsp_city_id" => $srch_city_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByStatus')
        {
            $output = public_path() . '/labels/members/allMembersByStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByStatus.jrxml'; 

            $conditions = array('jsp_member_status_id' => $srch_member_status_id);
        }

        $ext = "pdf";
       
        $report = new JasperPHP;
        $report->process
        (
            $input, 
            $output, 
            array('pdf'),
            $conditions,
            $database  
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=members_labels_'.date("Ymd_His").'.'.$ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($output.'.'.$ext));
        flush();
        readfile($output.'.'.$ext);
        unlink($output.'.'.$ext);
    }


    public function members(Request $request)
    {
        session(['srch_member_code'             => $request->get('code')]);
        session(['srch_member_cpf'              => $request->get('cpf')]);
        session(['srch_member_name'             => $request->get('name')]);
        session(['srch_member_plan_id'          => $request->get('plan_id')]);
        session(['srch_member_gender_id'        => $request->get('gender_id')]);
        session(['srch_member_region_id'        => $request->get('region_id')]);
        session(['srch_member_city_id'          => $request->get('city_id')]);
        session(['srch_member_status_id'        => $request->get('status_id')]);
        session(['srch_member_status_reason_id' => $request->get('member_status_reason_id')]);

        $members = $this->memberRepository->searchMembers();

        return view('members.search_results', compact('members'));
    }

    public function pc_partners()
    {
        $partner_type1 = $this->partner_typeRepository->findPartnerTypeById(1);
        
        $partner_type1_allpartnersbytype = $this->partnerRepository->allPartnersByTypeId(1);
    
        $partner_type1_allpartnersemailbytype = $this->partnerRepository->allPartnersEmailByType(1);
        
        $partner_type1_regions = $this->regionRepository->allRegions();
        $partner_type1_regions->load(['partners' => function($q) 
        {
            $q->wherePartnerTypeId(1);
            $q->get();
        }]);
        
        $partner_type1_cities = $this->cityRepository->allCities();
        $partner_type1_cities->load(['partners' => function($q) 
        {
            $q->wherePartnerTypeId(1);
            $q->get();
        }]);


        $partner_type2 = $this->partner_typeRepository->findPartnerTypeById(2);
        
        $partner_type2_allpartnersbytype = $this->partnerRepository->allPartnersByTypeId(2);
        
        $partner_type2_allpartnersemailbytype = $this->partnerRepository->allPartnersEmailByType(2);
        
        $partner_type2_regions = $this->regionRepository->allRegions();
        $partner_type2_regions->load(['partners' => function($q) 
        {
            $q->wherePartnerTypeId(2);
            $q->get();
        }]);
        
        $partner_type2_cities = $this->cityRepository->allCities();
        $partner_type2_cities->load(['partners' => function($q) 
        {
            $q->wherePartnerTypeId(2);
            $q->get();
        }]);


        $partner_type3 = $this->partner_typeRepository->findPartnerTypeById(3);
        
        $partner_type3_allpartnersbytype = $this->partnerRepository->allPartnersByTypeId(3);
        
        $partner_type3_allpartnersemailbytype = $this->partnerRepository->allPartnersEmailByType(3);
        
        $partner_type3_regions = $this->regionRepository->allRegions();
        $partner_type3_regions->load(['partners' => function($q) 
        {
            $q->wherePartnerTypeId(3);
            $q->get();
        }]);
        
        $partner_type3_cities = $this->cityRepository->allCities();
        $partner_type3_cities->load(['partners' => function($q) 
        {
            $q->wherePartnerTypeId(3);
            $q->get();
        }]);



        $partner_type_regions = $this->regionRepository->allRegions();
        $partner_type_regions->load(['partners' => function($q) 
        {
            $q->get();
        }]);
        
        $partner_type_cities = $this->cityRepository->allCities();
        $partner_type_cities->load(['partners' => function($q) 
        {
            $q->get();
        }]);

        return view('dashboard.partners', compact('partner_type1', 'partner_type1_allpartnersbytype', 'partner_type1_allpartnersemailbytype', 'partner_type1_regions', 'partner_type1_cities', 'partner_type2', 'partner_type2_allpartnersbytype', 'partner_type2_allpartnersemailbytype', 'partner_type2_regions', 'partner_type2_cities', 'partner_type3', 'partner_type3_allpartnersbytype', 'partner_type3_allpartnersemailbytype', 'partner_type3_regions', 'partner_type3_cities', 'partner_type_regions', 'partner_type_cities'));//
    }

    public function partners(Request $request)
    {
        session(['srch_partner_name'            => $request->get('name')]);
        session(['srch_partner_type_id'         => $request->get('partner_type_id')]);
        session(['srch_partner_region_id'       => $request->get('region_id')]);
        session(['srch_partner_city_id'         => $request->get('city_id')]);
        
        $partners = $this->partnerRepository->searchPartners();

        return view('partners.search_results', compact('partners'));
    }

    public function partners_reports(Request $request)
    {
        $rpt_model                       = $request->get('model');
        
        $srch_partner_type_id            = $request->get('partner_type_id');
        $srch_region_id                  = $request->get('region_id');
        $srch_city_id                    = $request->get('city_id');
        
        $database = \Config::get('database.connections.mysql');

        if($rpt_model=='allPartners')
        {
            $output = public_path() . '/reports/partners/allPartners_'.date("Ymd_His");  
            $input = public_path() . '/reports/partners/allPartners.jrxml'; 

            $conditions = array();
        }

        if($rpt_model=='allPartnersByType')
        {
            $output = public_path() . '/reports/partners/allPartnersByType_'.date("Ymd_His");  
            $input = public_path() . '/reports/partners/allPartnersByType.jrxml'; 

            $conditions = array('jsp_partner_type_id' => $srch_partner_type_id);
        }

        if($rpt_model=='allPartnersByRegionType')
        {
            $output = public_path() . '/reports/partners/allPartnersByRegionType_'.date("Ymd_His");  
            $input = public_path() . '/reports/partners/allPartnersByRegionType.jrxml'; 

            $conditions = array('jsp_partner_type_id' => $srch_partner_type_id, 'jsp_region_id' => $srch_region_id);
        }

        if($rpt_model=='allPartnersByRegion')
        {
            $output = public_path() . '/reports/partners/allPartnersByRegion_'.date("Ymd_His");  
            $input = public_path() . '/reports/partners/allPartnersByRegion.jrxml'; 

            $conditions = array('jsp_region_id' => $srch_region_id);
        }

        if($rpt_model=='allPartnersByCityType')
        {
            $output = public_path() . '/reports/partners/allPartnersByCityType_'.date("Ymd_His");  
            $input = public_path() . '/reports/partners/allPartnersByCityType.jrxml'; 

            $conditions = array('jsp_partner_type_id' => $srch_partner_type_id, 'jsp_city_id' => $srch_city_id);
        }

        if($rpt_model=='allPartnersByCity')
        {
            $output = public_path() . '/reports/partners/allPartnersByCity_'.date("Ymd_His");  
            $input = public_path() . '/reports/partners/allPartnersByCity.jrxml'; 

            $conditions = array('jsp_city_id' => $srch_city_id);
        }

        #dd($conditions);

        $ext = "pdf";
       
        $report = new JasperPHP;
        $report->process
        (
            $input, 
            $output, 
            array('pdf'),
            $conditions,
            $database  
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=partners_reports_'.date("Ymd_His").'.'.$ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($output.'.'.$ext));
        flush();
        readfile($output.'.'.$ext);
        unlink($output.'.'.$ext);
    }

    public function partners_labels(Request $request)
    {
        $rpt_model                       = $request->get('model');
        
        $srch_partner_type_id            = $request->get('partner_type_id');
        $srch_region_id                  = $request->get('region_id');
        $srch_city_id                    = $request->get('city_id');
        
        $database = \Config::get('database.connections.mysql');
                        
        if($rpt_model=='allMembersByPlanRegionStatus')
        {
            $output = public_path() . '/labels/members/allMembersByPlanRegionStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByPlanRegionStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_region_id" => $srch_region_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByPlanCityStatus')
        {
            $output = public_path() . '/labels/members/allMembersByPlanCityStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByPlanCityStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_city_id" => $srch_city_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByPlanStatus')
        {
            $output = public_path() . '/labels/members/allMembersByPlanStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByPlanStatus.jrxml'; 

            $conditions = array("jsp_plan_id" => $srch_plan_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByRegionStatus')
        {
            $output = public_path() . '/labels/members/allMembersByRegionStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByRegionStatus.jrxml'; 

            $conditions = array("jsp_region_id" => $srch_region_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByCityStatus')
        {
            $output = public_path() . '/labels/members/allMembersByCityStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByCityStatus.jrxml'; 

            $conditions = array("jsp_city_id" => $srch_city_id, "jsp_member_status_id" => $srch_member_status_id);
        }

        if($rpt_model=='allMembersByStatus')
        {
            $output = public_path() . '/labels/members/allMembersByStatus_'.date("Ymd_His");  
            $input = public_path() . '/labels/members/allMembersByStatus.jrxml'; 

            $conditions = array('jsp_member_status_id' => $srch_member_status_id);
        }

        if($rpt_model=='allPartnersByType')
        {
            $output = public_path() . '/labels/partners/allPartnersByType_'.date("Ymd_His");  
            $input = public_path() . '/labels/partners/allPartnersByType.jrxml'; 

            $conditions = array('jsp_partner_type_id' => $srch_partner_type_id);
        }

        if($rpt_model=='allPartners')
        {
            $output = public_path() . '/labels/partners/allPartners_'.date("Ymd_His");  
            $input = public_path() . '/labels/partners/allPartners.jrxml'; 

            $conditions = array();
        }

        if($rpt_model=='allPartnersByRegionType')
        {
            $output = public_path() . '/labels/partners/allPartnersByRegionType_'.date("Ymd_His");  
            $input = public_path() . '/labels/partners/allPartnersByRegionType.jrxml'; 

            $conditions = array('jsp_partner_type_id' => $srch_partner_type_id, 'jsp_region_id' => $srch_region_id);
        }

        if($rpt_model=='allPartnersByRegion')
        {
            $output = public_path() . '/labels/partners/allPartnersByRegion_'.date("Ymd_His");  
            $input = public_path() . '/labels/partners/allPartnersByRegion.jrxml'; 

            $conditions = array('jsp_region_id' => $srch_region_id);
        }

        if($rpt_model=='allPartnersByCityType')
        {
            $output = public_path() . '/labels/partners/allPartnersByCityType_'.date("Ymd_His");  
            $input = public_path() . '/labels/partners/allPartnersByCityType.jrxml'; 

            $conditions = array('jsp_partner_type_id' => $srch_partner_type_id, 'jsp_city_id' => $srch_city_id);
        }

        if($rpt_model=='allPartnersByCity')
        {
            $output = public_path() . '/labels/partners/allPartnersByCity_'.date("Ymd_His");  
            $input = public_path() . '/labels/partners/allPartnersByCity.jrxml'; 

            $conditions = array('jsp_city_id' => $srch_city_id);
        }

        $ext = "pdf";
       
        $report = new JasperPHP;
        $report->process
        (
            $input, 
            $output, 
            array('pdf'),
            $conditions,
            $database  
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=members_labels_'.date("Ymd_His").'.'.$ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($output.'.'.$ext));
        flush();
        readfile($output.'.'.$ext);
        unlink($output.'.'.$ext);
    }

}
