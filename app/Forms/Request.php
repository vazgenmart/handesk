<?php
/**
 * Created by PhpStorm.
 * User: vazge
 * Date: 11/23/2018
 * Time: 3:57 PM
 */

namespace App\Forms;


use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Request extends BaseModel
{
    protected $table = 'request';

    const TYPE = ['Right of access by the data subject', 'Right to rectification', 'Right to erasure (‘right to be forgotten’)', 'Right to restriction of processing', 'Right to data portability', 'Right to object', 'Other comment or question'];
    const COUNTRY = ['Austria', 'Belgium', 'Bulgaria', 'Croatia', 'Republic of Cyprus', 'Czech Republic', 'Denmark', 'Estonia', 'Finland', 'France', 'Germany', 'Greece', 'Hungary', 'Ireland', 'Italy', 'Latvia', 'Lithuania', 'Luxembourg', 'Malta', 'Netherlands', 'Poland', 'Portugal', 'Romania', 'Slovakia', 'Slovenia', 'Spain', 'Sweden', 'United Kingdom', 'Other'];
}