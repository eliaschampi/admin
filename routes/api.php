<?php

use App\Http\Controllers\AilmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttentionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CashActionTypeController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExtraInfoController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FamilyworkController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\IncomeDetailController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\JustificationController;
use App\Http\Controllers\OpController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "auth"], function () {
    Route::post("login", [AuthController::class, "login"]);
    Route::put("recover", [AuthController::class, "recover"]);
    Route::post("refresh", [AuthController::class, "refresh"]);
});

Route::group(["middleware" => ["withkey"]], function () {
    //cedp p1
    Route::get("/attendance/{section_code}/{date}/{priority}", [AttendanceController::class, "fetchBySection"]);
    Route::get("/attendance/{entity_identifier}/{from}/{to}/{priority}", [AttendanceController::class, "fetchByEntity"]);
    Route::get("/payments/{register_code}", [IncomeDetailController::class, "fetchStudentPayments"]);
    Route::get("/register_all/{dni}", [RegisterController::class, "fetchByStudent"]);

    //dw cedp
    Route::get("/inspection_print/{code}", [InspectionController::class, "print"]);
    Route::get("/attention_print/{code}", [AttentionController::class, "print"]);
    Route::get("/incidence_print/{code}", [IncidenceController::class, "print"]);
    Route::get("/incidence_dw/{code}", [IncidenceController::class, "downloadAttached"]);
    Route::get("/attention_dw/{code}", [AttentionController::class, "downloadAttached"]);
    //cedp for aeduca user
    Route::get("/cedp/incidence/{dni}/{show_all?}", [IncidenceController::class, "fetchByEntity"]);
    Route::get("/cedp/attention/{dni}/{show_all?}", [AttentionController::class, "fetchByEntity"]);
    Route::get("/cedp/inspection/{dni}/{type}", [InspectionController::class, "fetchByEntity"]);
});

//group
Route::group(["middleware" => ["jwt.auth"]], function () {

    //mark
    Route::get("/counts", [SystemController::class, "counts"]);
    Route::post("/support", [SystemController::class, "sendMessageToElias"]);

    //auth
    Route::post("logout", [AuthController::class, "logout"]);
    Route::post("check", [AuthController::class, "check"]);

    //cycle
    Route::get("/ciclo", [CycleController::class, "index"]);
    Route::post("/ciclo", [CycleController::class, "store"]);
    Route::put("/ciclo/{code}", [CycleController::class, "update"]);
    Route::get("reporte/ciclo/{cycle_code}", [CycleController::class, "print"]);

    //branch
    Route::get("/sedes", [BranchController::class, "index"]);
    Route::post("/sedes", [BranchController::class, "store"]);
    Route::put("/sedes/{code}", [BranchController::class, "update"]);

    //courses y extras
    Route::resource("/curso", CourseController::class)->except(["create", "show", "edit"]);

    //config
    Route::get("/configuracion/{tags}", [ConfigController::class, "tags"]);
    Route::get("/configuracion_p/{tags}", [ConfigController::class, "tagsPlucked"]);
    Route::put("/configuracion/{config_key}", [ConfigController::class, "update"]);
    Route::post("/compania", [ConfigController::class, "updateMany"]);

    //system
    Route::get("/distritos", [SystemController::class, "ubigeo"]);
    Route::post("/soporte", [SystemController::class, "support"]);
    Route::post("/imagen", [SystemController::class, "upload"]);
    Route::get("/card/{mode}/{identifier}/{subtitle?}", [SystemController::class, "printCard"]);

    //user
    Route::resource("/usuario", UserController::class)->except(["create", "edit"]);
    Route::get("/roles", [UserController::class, "roles"]);
    Route::put("/usuario/image/{code}", [UserController::class, "changeImage"]);
    Route::put("/usuario/password/{code}", [UserController::class, "changePassword"]);
    Route::put("/usuario_state/{code}", [UserController::class, "changeState"]);
    Route::put("/usuario_branch/{branch}", [UserController::class, "changeBranch"]);
    Route::put("/usuario_year", [UserController::class, "changeCurrentYear"]);

    //degree
    Route::get("/grados/{cycle_code}", [DegreeController::class, "index"]);
    Route::get("/grado/{code}", [DegreeController::class, "degree"]);

    //section
    Route::resource("/section", SectionController::class)->except(["edit", "show", "create"]);
    Route::get("/section_dg/{degree_code}", [SectionController::class, "fetchByDegree"]);
    Route::get("/migrate/{degree_code}", [SectionController::class, "fetchForMigrate"]);

    //person
    Route::get("/person/{type}/{dni}", [PersonController::class, "show"]);
    Route::delete("/person/{dni}", [PersonController::class, "destroy"]);

    //profile
    Route::resource("/profile", ProfileController::class)->only(["store", "update", "destroy"]);
    Route::put("/profile/image/{code}", [ProfileController::class, "changeImage"]);
    Route::get("/profile_pdf/{dni}", [ProfileController::class, "printInfo"]);

    //register
    Route::get("/register/{section_code}/{inactives}", [RegisterController::class, "fetchBySection"]);
    Route::get("/register/{dni}/{states}/{mod}", [RegisterController::class, "fetch"]);
    Route::get("/register_has_cache", [RegisterController::class, "hasOnCache"]);
    Route::post("/register", [RegisterController::class, "store"]);
    Route::delete("/register/{code}", [RegisterController::class, "destroy"]);
    Route::post("/register_m", [RegisterController::class, "storeMany"]);

    //attendace
    Route::get("/register_asis/{section_code}/{priority}", [RegisterController::class, "fetchForAttendance"]);

    //teacher (usar index para imprimir)
    Route::resource("/teacher", TeacherController::class)->except(["edit", "create", "destroy"]);
    Route::get("/teacher/search/{name}", [TeacherController::class, "search"]);
    Route::get("/teacher/self/{dni}", [TeacherController::class, "self"]);
    Route::get("/teacher_cycle/{c_code}", [TeacherController::class, "fetchByCycle"]);
    Route::get("/teacher_spe/{spe}", [TeacherController::class, "fetchBySpe"]);
    Route::get("/teacher_pdf/{dni}", [TeacherController::class, "printInfo"]);
    Route::put("/teacher_state/{dni}", [TeacherController::class, "changeState"]);

    //student
    Route::resource("/student", StudentController::class)->only(["store", "show", "update"]);
    Route::get("/student/search/{branch_code}/{only_current_register}/{name}", [StudentController::class, "search"]);
    Route::put("/student/branch/{dni}", [StudentController::class, "updatebranch"]);
    Route::get("/student_pdf/{dni}", [StudentController::class, "printInfo"]);

    Route::resource("/extrainfo", ExtraInfoController::class)->only(["show", "store"]);
    // ailemnt
    Route::resource("/ailment", AilmentController::class)->except(["index", "create", "edit"]);
    Route::post("/ailment_up", [AilmentController::class, "uploadAttached"]);
    Route::get("/ailment_dw/{code}", [AilmentController::class, "downloadAttached"]);

    //work
    Route::resource("/familywork", FamilyworkController::class)->only(["show", "store"]);

    //apoderado
    Route::resource("/family", FamilyController::class)->only(["show", "store", "update"]);
    Route::get("/family/search/{name}", [FamilyController::class, "search"]);
    Route::get("/family/self/{dni}", [FamilyController::class, "self"]);
    Route::get("/family_sec/{section_code}", [FamilyController::class, "fetchBySection"]);
    Route::get("/family_pdf/{dni}", [FamilyController::class, "printInfo"]);

    //family_student
    Route::get("/family_s/{student_dni}", [FamilyController::class, "fetchByStudent"]);
    Route::get("/family_st/{family_dni}", [FamilyController::class, "fetchStudents"]);
    Route::put("/family_s", [FamilyController::class, "addStudent"]);
    Route::delete("/family_s/{family_dni}/{student_dni}", [FamilyController::class, "removeStudent"]);

    //op
    Route::post("/op", [OpController::class, "store"]);

    //schedule
    Route::resource("/schedule", ScheduleController::class)->only(["store", "update", "destroy"]);
    Route::get("/schedule/{section_code}", [ScheduleController::class, "fetchMain"]);
    Route::get("/schedule/teacher/{teacher_dni}", [ScheduleController::class, "fetchByTeacher"]);

    Route::resource("/customer", CustomerController::class)->except(["edit", "create", "show"]);
    Route::resource("/cat", CashActionTypeController::class)->only(["show", "store", "update"]);

    //cash
    Route::get("/cash/{date?}", [CashController::class, "fetch"]);
    Route::get("/cash_month/{month}", [CashController::class, "fetchByMonth"]);
    Route::get("/cash_last", [CashController::class, "lastCash"]);
    Route::get("/cash_export", [CashController::class, "exportToExcel"]);
    Route::get("/cash_chart", [CashController::class, "fetchChart"]);
    Route::post("/cash", [CashController::class, "openCash"]);
    Route::put("/cash/{code}", [CashController::class, "toggleCash"]);
    Route::put("/surrender/{code}", [CashController::class, "surrender"]);

    Route::get("/income/{from}/{to}", [IncomeController::class, "fetchByDates"]);
    Route::post("/income", [IncomeController::class, "store"]);
    Route::put("/income/{code}", [IncomeController::class, "canceled"]);
    Route::get("/income/canceled", [IncomeController::class, "canceleds"]);
    Route::get("/income_excel/{from}/{to}", [IncomeController::class, "exportToExcel"]);
    Route::get("/serie/{type}", [IncomeController::class, "fetchNewIncomeNumber"]);

    Route::get("/income_detail", [IncomeDetailController::class, "showFromCache"]);
    Route::get("/income_detail/{code}", [IncomeDetailController::class, "fetchByIncome"]);
    Route::put("/income_detail", [IncomeDetailController::class, "storeInCache"]);
    Route::delete("/income_detail", [IncomeDetailController::class, "removeAllFromCache"]);
    Route::put("/income_detail/{id}", [IncomeDetailController::class, "removeItemFromCache"]);

    // expense
    Route::resource("/expense", ExpenseController::class)->except(["index", "create", "edit"]);
    Route::get("/expense/{from}/{to}", [ExpenseController::class, "fetchByDates"]);
    Route::get("/expense_chart/{from}/{to}", [ExpenseController::class, "fetchGroupedByType"]);
    Route::get("/expense_print/{code}", [ExpenseController::class, "print"]);
    Route::get("/expense_export/{from}/{to}", [ExpenseController::class, "exportToExcel"]);

    //attendance
    Route::get("/attendance_t/{date}", [AttendanceController::class, "fetchForTeacherByDate"]);
    Route::get("/attendance_dw/{entity_identifier}/{from_date}/{to_date}", [AttendanceController::class, "exportToExcel"]);
    Route::get("/attendance_sw/{section_code}/{date}/{priority}", [AttendanceController::class, "exportToExcelBySection"]);
    Route::get("/attendance_ab/{date}/{priority}", [AttendanceController::class, "fetchAbsences"]);
    Route::get("/attendance_chart", [AttendanceController::class, "fetchForChart"]);
    Route::post("/attendance", [AttendanceController::class, "store"]);
    Route::post("/attendance_auto", [AttendanceController::class, "auto"]);
    Route::put("/attendance/{code}", [AttendanceController::class, "update"]);

    //justification
    Route::get("/cedp/justification/{dni}", [JustificationController::class, "fetchByEntity"]);
    Route::get("/justification/{code}", [JustificationController::class, "downloadAttached"]);
    Route::post("/justification", [JustificationController::class, "store"]);
    Route::put("/justification/{code}/{aprove}", [JustificationController::class, "toggle"]);

    // incidence
    Route::get("/incidence/{month}", [IncidenceController::class, "fetchByMonth"]);
    Route::post("/incidence", [IncidenceController::class, "store"]);
    Route::put("/incidence/{code}", [IncidenceController::class, "update"]);
    Route::delete("/incidence/{code}", [IncidenceController::class, "destroy"]);

    // attention
    Route::get("/attention/{month}", [AttentionController::class, "fetchByMonth"]);
    Route::post("/attention", [AttentionController::class, "store"]);
    Route::put("/attention/{code}", [AttentionController::class, "update"]);
    Route::delete("/attention/{code}", [AttentionController::class, "destroy"]);

    Route::get("/inspection", [InspectionController::class, "fetchStates"]);
    Route::get("/inspection/{type}", [InspectionController::class, "fetchByType"]);
    Route::post("/inspection", [InspectionController::class, "store"]);
    Route::put("/inspection/{code}", [InspectionController::class, "update"]);
    Route::delete("/inspection/{code}", [InspectionController::class, "destroy"]);
});
