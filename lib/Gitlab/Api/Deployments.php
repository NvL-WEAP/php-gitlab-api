<?php

namespace Gitlab\Api;

use Symfony\Component\OptionsResolver\Options;

class Deployments extends AbstractApi
{
    /**
     * @param int $project_id
     * @param array $parameters
     * @return mixed
     */
    public function all($project_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $datetimeNormalizer = function (Options $resolver, \DateTimeInterface $value) {
            return $value->format('c');
        };

        $resolver->setDefined('order_by')
            ->setAllowedValues('order_by', ['id', 'created_at', 'updated_at']);

        $resolver->setDefined('sort')
            ->setAllowedValues('sort', ['asc', 'desc']);

        $resolver->setDefined('updated_after')
            ->setAllowedTypes('updated_after', \DateTimeInterface::class)
            ->setNormalizer('updated_after', $datetimeNormalizer);

        $resolver->setDefined('updated_before')
            ->setAllowedTypes('updated_before', \DateTimeInterface::class)
            ->setNormalizer('updated_before', $datetimeNormalizer);

        return $this->get($this->getProjectPath($project_id, 'deployments'), $resolver->resolve($parameters));
    }

    /**
     * @param int $project_id
     * @param string $deployment_id
     * @return mixed
     */
    public function show($project_id, $deployment_id)
    {
        return $this->get($this->getProjectPath($project_id, 'deployments/' . $deployment_id));
    }
}
