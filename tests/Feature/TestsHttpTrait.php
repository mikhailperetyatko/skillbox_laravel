<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait TestsHttpTrait
{
    public $testingModel;
    public $user;
    
    public function setTestingModel(array $model)
    {
        unset($model['updated_at']);
        $this->testingModel = $model;
        if (! empty($this->user)) $this->setOwnerOfModel();
    }
    
    public function withFacrotyCreate(string $class, array $attr = [])
    {
        $this->setTestingModel(factory($class)->create($attr)->toArray());
        return $this;
    }
        
    public function withFacrotyMake(string $class, array $attr = [])
    {
        $this->setTestingModel(factory($class)->make($attr));
        return $this;
    }

    public function withFacrotyRaw(string $class, array $attr = [])
    {
        $this->setTestingModel(factory($class)->raw($attr));
        return $this;
    }     
    
    public function withPostRequest(string $url, array $attr = [])
    {
        $values = array_merge($this->testingModel, $attr);
        $this->post($url, $values);
        return $this;
    }
    
    public function withPatchRequest(string $url, array $attr)
    {
        $this->testingModel = array_merge($this->testingModel, $attr);
        $this->patch($url . '/' . $this->testingModel['slug'], $this->testingModel);
        return $this;
    }
    
    public function withDeleteRequest(string $url)
    {
        $this->delete($url . '/' . $this->testingModel['slug']);
        return $this;
    }
    
    public function withUser(\App\User $needUser = null)
    {
        $user = is_null($needUser) ? factory(\App\User::class)->create() : $needUser;
        $this->actingAs($user);
        $this->user = $user;
        return $this;
    }
    
    public function setOwnerOfModel(\App\User $user = null)
    {
        $this->testingModel['owner_id'] = is_null($user) ? $this->user->id : $user->id;
        return $this;
    }    
}